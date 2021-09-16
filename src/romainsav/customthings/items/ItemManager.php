<?php

namespace romainsav\customthings\items;

use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\network\mcpe\convert\ItemTranslator;
use pocketmine\network\mcpe\convert\ItemTypeDictionary;
use pocketmine\network\mcpe\protocol\ItemComponentPacket;
use pocketmine\network\mcpe\protocol\types\ItemComponentPacketEntry;
use pocketmine\network\mcpe\protocol\types\ItemTypeEntry;
use pocketmine\utils\SingletonTrait;
use ReflectionClass;
use ReflectionProperty;
use Ahc\Json\Comment as CommentedJsonDecoder;
use romainsav\customthings\items\classes\ArmorClass;
use romainsav\customthings\items\classes\ItemClass;
use romainsav\customthings\items\classes\SwordClass;
use romainsav\customthings\items\classes\ToolClass;
use romainsav\customthings\items\json\ArmorJson;
use romainsav\customthings\items\json\BaseJson;
use romainsav\customthings\items\json\ToolJson;
use romainsav\customthings\items\properties\ArmorProperties;
use romainsav\customthings\items\properties\ItemProperties;
use romainsav\customthings\items\properties\ToolProperties;
use romainsav\customthings\Loader;
use romainsav\customthings\mapper\JsonMapper;
use romainsav\customthings\mapper\JsonMapper_Exception;
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
            switch ($class->type) {
                case "helmet":
                case "chestplate":
                case "leggings":
                case "boots":
                    $item = new ArmorClass(new ArmorProperties($mapper->map($class, new ArmorJson())));
                    break;
                case "pickaxe":
                case "shovel":
                case "axe":
                    $item = new ToolClass(new ToolProperties($mapper->map($class, new ToolJson())));
                    break;
                case "sword":
                    $item = new SwordClass(new ToolProperties($mapper->map($class, new ToolJson())));
                    break;
                default:
                    $item = new ItemClass(new ItemProperties($mapper->map($class, new BaseJson())));
            }
        } catch (JsonMapper_Exception $exception) {
            throw new JsonMapper_Exception("Invalid manifest.json contents: " . $exception->getMessage(), 0, $exception);
        }

        $this->addValues($item->getProperties()->getId());

        $this->itemTypesEntries[] = new ItemTypeEntry($item->getProperties()->getName(), $item->getProperties()->getId(), true);
        $this->packetEntries[] = new ItemComponentPacketEntry($item->getProperties()->getName(), $item->getProperties()->getComponent());

        $this->registered[] = $item;

        ItemFactory::registerItem($item, true);
        Item::addCreativeItem($item);
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

        $this->itemTypesEntries = $this->itemTypesMap->getValue(ItemTypeDictionary::getInstance());
    }

    private function saveReflectionClass(): void {
        $this->netToCoreMapping->setValue(ItemTranslator::getInstance(), $this->netToCoreValues);
        $this->coreToNetMapping->setValue(ItemTranslator::getInstance(), $this->coreToNetValues);
        $this->itemTypesMap->setValue(ItemTypeDictionary::getInstance(), $this->itemTypesEntries);
        $this->packet = ItemComponentPacket::create($this->packetEntries);
    }

    /**
     * @return ItemComponentPacket
     */
    public function getPacket(): ItemComponentPacket {
        return $this->packet;
    }
}