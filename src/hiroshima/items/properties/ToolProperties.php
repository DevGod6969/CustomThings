<?php

namespace hiroshima\items\properties;

use hiroshima\items\json\BaseJson;
use hiroshima\items\json\ToolJson;
use pocketmine\nbt\tag\CompoundTag;

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
        return CompoundTag::create()
            ->setTag("components", CompoundTag::create()
                ->setTag("item_properties", CompoundTag::create()
                    ->setByte("allow_off_hand", true)
                    ->setByte("hand_equipped", true)
                    ->setInt("max_stack_size", 1)
                    ->setInt("creative_category", 3)
                    ->setString("creative_group", $this->creative_group)
                    ->setInt("damage", $this->damage)
                    ->setString("enchantable_slot", $this->getType())
                    ->setInt("enchantable_value", 10)
                    ->setByte("can_destroy_in_creative", !($this->getType() === "sword"))
                )
                ->setTag("minecraft:icon", CompoundTag::create()->setString("texture", $this->getTexture()))
                ->setTag("minecraft:display_name", CompoundTag::create()->setString("value", "item." . $this->getName() . ".name"))
                ->setTag("minecraft:durability", CompoundTag::create()->setInt("max_durability", $this->getMaxDurability()))
                ->setTag("minecraft:weapon", CompoundTag::create()
                    ->setTag("on_hurt_entity", CompoundTag::create()->setString("event", "hiroshima:hurt_entity"))
                    ->setTag("on_not_hurt_entity", CompoundTag::create()->setString("event", "hiroshima:not_hurt_entity"))
                    ->setTag("on_hit_block", CompoundTag::create()->setString("event", "hiroshima:hit_block"))
                )
            )
            ->setInt("id", $this->getId())
            ->setString("name", $this->getName());
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