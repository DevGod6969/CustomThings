<?php

namespace hiroshima\items\properties;

use pocketmine\inventory\Inventory;
use pocketmine\nbt\tag\CompoundTag;

class ItemProperties extends BaseProperties {

    public function buildComponent(): CompoundTag {
        return CompoundTag::create()
            ->setTag("components", CompoundTag::create()
                ->setTag("item_properties", CompoundTag::create()
                    ->setByte("allow_off_hand", true)
                    ->setByte("hand_equipped", false)
                    ->setInt("max_stack_size", Inventory::MAX_STACK)
                    ->setInt("creative_category", 4)
                )
                ->setTag("minecraft:icon", CompoundTag::create()->setString("texture", $this->getTexture()))
                ->setTag("minecraft:display_name", CompoundTag::create()->setString("value", "item." . $this->getName() . ".name"))
            )
            ->setInt("id", $this->getId())
            ->setString("name", $this->getName());
    }
}