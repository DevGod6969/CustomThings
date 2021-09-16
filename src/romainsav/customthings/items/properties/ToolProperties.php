<?php

namespace romainsav\customthings\items\properties;

use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use romainsav\customthings\items\json\ToolJson;

class ToolProperties extends BaseProperties {

    /** @var int */
    private int $max_durability;

    /** @var int */
    private int $damage;

    /** @var string */
    private string $creative_group;

    /** @var string[][] */
    private const TOOL_INFO = [
        "sword" => ["creative_group" => "itemGroup.name.sword",],
        "shovel" => ["creative_group" => "itemGroup.name.shovel",],
        "pickaxe" => ["creative_group" => "itemGroup.name.pickaxe",],
        "axe" => ["creative_group" => "itemGroup.name.axe",],
    ];

    public function __construct(ToolJson $toolJson) {
        $this->type = $toolJson->type;
        $this->max_durability = $toolJson->max_durability;
        $this->damage = $toolJson->damage;
        $this->creative_group = $this->getToolInfos()["creative_group"];

        parent::__construct($toolJson);
    }

    public function buildComponent(): CompoundTag {
        return new CompoundTag("", [
            new CompoundTag("components", [
                new CompoundTag("item_properties", [
                    new ByteTag("allow_off_hand", true),
                    new ByteTag("hand_equipped", true),
                    new IntTag("max_stack_size", 1),
                    new IntTag("creative_category", 3),
                    new IntTag("damage", $this->damage),
                    new StringTag("creative_group", $this->creative_group),
                    new StringTag("enchantable_slot", $this->getType()),
                    new StringTag("enchantable_value", 10),
                    new ByteTag("can_destroy_in_creative", !($this->getType() === "sword"))
                ]),
                new CompoundTag("minecraft:icon", [new StringTag("texture", $this->getTexture())]),
                new CompoundTag("minecraft:display_name", [new StringTag("value", "item." . $this->getName() . ".name")]),
                new CompoundTag("minecraft:durability", [new IntTag("max_durability", $this->getMaxDurability())]),
                new CompoundTag("minecraft:weapon", [
                    new CompoundTag("on_hurt_entity", [new StringTag("event", "hiroshima:hurt_entity")]),
                    new CompoundTag("on_not_hurt_entity", [new StringTag("event", "hiroshima:not_hurt_entity")]),
                    new CompoundTag("on_hit_block", [new StringTag("event", "hiroshima:hit_block")])
                ]),
            ]),
            new IntTag("id", $this->getId()),
            new StringTag("name", $this->getName())
        ]);
    }

    /**
     * @return string[]
     */
    private function getToolInfos(): array {
        return self::TOOL_INFO[$this->getType()];
    }

    /**
     * @return int
     */
    public function getMaxDurability(): int {
        return $this->max_durability;
    }

    /**
     * @return int
     */
    public function getDamage(): int {
        return $this->damage;
    }
}