<?php

namespace romainsav\customthings\items\classes;

use romainsav\customthings\items\properties\ToolProperties;
use pocketmine\block\BlockToolType;
use pocketmine\item\TieredTool;

class ToolClass extends TieredTool {

    /** @var ToolProperties */
    private ToolProperties $properties;

    public function __construct(ToolProperties $properties) {
        $this->properties = $properties;

        parent::__construct($properties->getId(), $properties->getMeta(), $properties->getName(), self::TIER_DIAMOND);
    }

    public function getBlockToolType(): int {
        switch ($this->getProperties()->getType()){
            case "pickaxe":
                return BlockToolType::TYPE_PICKAXE;
            case "axe":
                return BlockToolType::TYPE_AXE;
            case "shovel":
                return BlockToolType::TYPE_SHOVEL;
            default:
                return BlockToolType::TYPE_HOE;
        }
    }

    /**
     * @return int
     */
    public function getMaxDurability(): int {
        return $this->getProperties()->getMaxDurability();
    }

    /**
     * @return int
     */
    public function getMaxStackSize(): int {
        return 1;
    }
    /**
     * @return ToolProperties
     */
    public function getProperties(): ToolProperties {
        return $this->properties;
    }
}