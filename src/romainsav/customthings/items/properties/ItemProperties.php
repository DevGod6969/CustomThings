<?php

namespace romainsav\customthings\items\properties;

use pocketmine\inventory\Inventory;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;

class ItemProperties extends BaseProperties {

    public function buildComponent(): CompoundTag {
        return new CompoundTag("", [
            new CompoundTag("components", [
                new CompoundTag("item_properties", [
                    new ByteTag("allow_off_hand", true),
                    new ByteTag("hand_equipped", false),
                    new IntTag("max_stack_size", Inventory::MAX_STACK),
                    new IntTag("creative_category", 4),
                ]),
                new CompoundTag("minecraft:icon", [new StringTag("texture", $this->getTexture())]),
                new CompoundTag("minecraft:display_name", [new StringTag("value", "item." . $this->getName() . ".name")])
            ]),
            new IntTag("id", $this->getId()),
            new StringTag("name", $this->getName())
        ]);
    }
}