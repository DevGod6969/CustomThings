<?php

namespace hiroshima\items;

use hiroshima\items\classes\ArmorClass;
use hiroshima\items\classes\ItemClass;
use hiroshima\items\classes\SwordClass;
use hiroshima\items\classes\ToolClass;
use hiroshima\items\json\ArmorJson;
use hiroshima\items\json\BaseJson;
use hiroshima\items\json\ToolJson;
use hiroshima\items\properties\ArmorProperties;
use hiroshima\items\properties\ItemProperties;
use hiroshima\items\properties\ToolProperties;
use hiroshima\Loader;
use JsonMapper;
use JsonMapper_Exception;
use pocketmine\inventory\CreativeInventory;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\network\mcpe\convert\GlobalItemTypeDictionary;
use pocketmine\network\mcpe\convert\ItemTranslator;
use pocketmine\network\mcpe\protocol\ItemComponentPacket;
use pocketmine\network\mcpe\protocol\serializer\ItemTypeDictionary;
use pocketmine\network\mcpe\protocol\types\ItemComponentPacketEntry;
use pocketmine\network\mcpe\protocol\types\ItemTypeEntry;
use pocketmine\utils\SingletonTrait;
use ReflectionClass;
use ReflectionProperty;
use Ahc\Json\Comment as CommentedJsonDecoder;
use stdClass;

class ItemManager {

    use SingletonTrait;

    /** @var array|mixed */
    private array $items;

    /** @var Item[] */
    private array $registered = [];

    /** @var ReflectionProperty */
    public ReflectionProperty $coreToNetMapping;

    /** @var array */
    public array $coreToNetValues = [];

    /** @var ReflectionProperty */
    public ReflectionProperty $netToCoreMapping;

    /** @var array */
    public array $netToCoreValues = [];

    /** @var ReflectionProperty */
    public ReflectionProperty $itemTypesMap;

    /** @var ItemTypeEntry[] */
    public array $itemTypesEntries = [];

    /** @var ItemComponentPacketEntry[] */
    public array $packetEntries = [];

    /** @var ItemComponentPacket */
    public ItemComponentPacket $packet;

    /**
     * @throws JsonMapper_Exception
     */
    public function __construct() {
        self::setInstance($this);
        $this->items = (new CommentedJsonDecoder())->decode(file_get_contents(Loader::getInstance()->getDataFolder() . "items.json"));

        $this->loadReflectionClass();

        foreach ($this->items as $itemData) {
            $this->loadItem($itemData);
        }

        $this->saveReflectionClass();
    }

    /**
     * @param stdClass $class
     * @throws JsonMapper_Exception
     */
    private function loadItem(stdClass $class) {
        $mapper = new JsonMapper();
        $mapper->bExceptionOnUndefinedProperty = true;
        $mapper->bExceptionOnMissingData = true;

        try {
            /** @var ArmorClass|ToolClass|SwordClass|ItemClass $item */
            $item = match ($class->type) {
                "helmet", "chestplate", "leggings", "boots" => new ArmorClass(new ArmorProperties($mapper->map($class, new ArmorJson()))),
                "pickaxe", "shovel", "axe" => new ToolClass(new ToolProperties($mapper->map($class, new ToolJson()))),
                "sword" => new SwordClass(new ToolProperties($mapper->map($class, new ToolJson()))),
                default => new ItemClass(new ItemProperties($mapper->map($class, new BaseJson()))),
            };
        } catch (JsonMapper_Exception $exception) {
            throw new JsonMapper_Exception("Invalid manifest.json contents: " . $exception->getMessage(), 0, $exception);
        }

        $this->addValues($item->getProperties()->getId());

        $this->itemTypesEntries[] = new ItemTypeEntry($item->getProperties()->getName(), $item->getProperties()->getId(), true);
        $this->packetEntries[] = new ItemComponentPacketEntry($item->getProperties()->getName(), $item->getProperties()->getComponent());

        $this->registered[] = $item;

        ItemFactory::getInstance()->register($item, true);
        CreativeInventory::getInstance()->add($item);
    }


    /**
     * @return ItemTypeEntry[]
     */
    public function getEntries(): array {
        return $this->itemTypesEntries;
    }

    /**
     * @param int $id
     */
    private function addValues(int $id): void {
        $this->coreToNetValues[$id] = $id;
        $this->netToCoreValues[$id] = $id;
    }

    private function loadReflectionClass(): void {
        $ref1 = new ReflectionClass(ItemTranslator::class);
        $this->coreToNetMapping = $ref1->getProperty("simpleCoreToNetMapping");
        $this->netToCoreMapping = $ref1->getProperty("simpleNetToCoreMapping");
        $this->coreToNetMapping->setAccessible(true);
        $this->netToCoreMapping->setAccessible(true);

        $this->coreToNetValues = $this->coreToNetMapping->getValue(ItemTranslator::getInstance());
        $this->netToCoreValues = $this->netToCoreMapping->getValue(ItemTranslator::getInstance());

        $ref2 = new ReflectionClass(ItemTypeDictionary::class);
        $this->itemTypesMap = $ref2->getProperty("itemTypes");
        $this->itemTypesMap->setAccessible(true);

        $this->itemTypesEntries = $this->itemTypesMap->getValue(GlobalItemTypeDictionary::getInstance()->getDictionary());
    }

    private function saveReflectionClass(): void {
        $this->netToCoreMapping->setValue(ItemTranslator::getInstance(), $this->netToCoreValues);
        $this->coreToNetMapping->setValue(ItemTranslator::getInstance(), $this->coreToNetValues);
        $this->itemTypesMap->setValue(GlobalItemTypeDictionary::getInstance()->getDictionary(), $this->itemTypesEntries);
        $this->packet = ItemComponentPacket::create($this->packetEntries);
    }

    /**
     * @return ItemComponentPacket
     */
    public function getPacket(): ItemComponentPacket {
        return $this->packet;
    }
}