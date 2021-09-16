<?php

namespace romainsav\customthings\items\properties;

use pocketmine\inventory\ArmorInventory;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use romainsav\customthings\items\json\ArmorJson;

class ArmorProperties extends BaseProperties {

    /** @var int */
    private int $max_durability;

    /** @var int */
    private int $defense_point;

    /** @var int */
    private int $protection;

    /** @var string */
    private string $creative_group;

    /** @var int */
    private int $slot;

    /** @var string[][] */
    private const ARMOR_INFO = [
        "helmet" => [
            "creative_group" => "itemGroup.name.helmet",
            "enchantable" => "armor_head",
            "slot" => 2,
        ],
        "chestplate" => [
            "creative_group" => "itemGroup.name.chestplate",
            "enchantable" => "armor_torso",
            "slot" => 3,
        ],
        "leggings" => [
            "creative_group" => "itemGroup.name.leggings",
            "enchantable" => "armor_legs",
            "slot" => 4,
        ],
        "boots" => [
            "creative_group" => "itemGroup.name.boots",
            "enchantable" => "armor_feet",
            "slot" => 5,
        ],
    ];

    public function __construct(ArmorJson $armorJson) {
        $this->type = $armorJson->type;
        $this->max_durability = $armorJson->max_durability;
        $this->defense_point = $armorJson->defense_point;
        $this->protection = $armorJson->protection;
        $this->creative_group = $this->getArmorInfos()["creative_group"];
        $this->slot = $this->getArmorInfos()["slot"];
        parent::__construct($armorJson);
    }

    public function buildComponent(): CompoundTag {
        return new CompoundTag("", [
            new CompoundTag("components", [
                new CompoundTag("item_properties", [
                    new ByteTag("allow_off_hand", true),
                    new ByteTag("hand_equipped", false),
                    new IntTag("max_stack_size", 1),
                    new IntTag("creative_category", 3),
                    new StringTag("creative_group", $this->creative_group),
                    new StringTag("enchantable_slot", $this->getArmorInfos()["enchantable"]),
                    new StringTag("enchantable_value", 10)
                ]),
                new CompoundTag("minecraft:icon", [new StringTag("texture", $this->getTexture())]),
                new CompoundTag("minecraft:display_name", [new StringTag("value", "item." . $this->getName() . ".name")]),
                new CompoundTag("minecraft:armor", [
                    new IntTag("protection", $this->getProtection()),
                    new StringTag("texture_type", "diamond")
                ]),
                new CompoundTag("minecraft:durability", [new IntTag("max_durability", $this->getMaxDurability())]),
                new CompoundTag("minecraft:wearable", [
                    new ByteTag("dispensable", true),
                    new IntTag("slot", $this->slot)
                ]),
            ]),
            new IntTag("id", $this->getId()),
            new StringTag("name", $this->getName())
        ]);
    }

    /**
     * @return string[]
     */
    private function getArmorInfos(): array {
        return self::ARMOR_INFO[$this->getType()];
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
    public function getSlot(): int {
        return [
            5 => ArmorInventory::SLOT_FEET,
            3 => ArmorInventory::SLOT_CHEST,
            4 => ArmorInventory::SLOT_LEGS,
            2 => ArmorInventory::SLOT_HEAD,
        ][$this->slot];
    }

    /**
     * @return int
     */
    public function getDefensePoint(): int {
        return $this->defense_point;
    }

    /**
     * @return int
     */
    public function getProtection(): int {
        return $this->protection;
    }
}