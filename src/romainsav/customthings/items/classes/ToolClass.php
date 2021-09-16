<?php

namespace romainsav\customthings\items\classes;

use pocketmine\block\Block;
use pocketmine\block\BlockToolType;
use pocketmine\entity\Entity;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\TieredTool;
use pocketmine\item\ToolTier;
use romainsav\customthings\items\properties\ToolProperties;

class ToolClass extends TieredTool {

    /** @var ToolProperties */
    private ToolProperties $properties;

    public function __construct(ToolProperties $properties) {
        $this->properties = $properties;

        parent::__construct(
            new ItemIdentifier($properties->getId(), $properties->getMeta()),
            $properties->getName(),
            ToolTier::DIAMOND()
        );
    }

    public function getBlockToolType(): int {
        return match ($this->getProperties()->getType()) {
            "pickaxe" => BlockToolType::PICKAXE,
            "axe" => BlockToolType::AXE,
            "shovel" => BlockToolType::SHOVEL,
            default => BlockToolType::HOE,
        };
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
     * @return int
     */
    public function getBlockToolHarvestLevel(): int {
        return $this->getTier()->getHarvestLevel();
    }

    /**
     * @return int
     */
    public function getAttackPoints(): int {
        return $this->getTier()->getBaseAttackPoints() - 1;
    }

    /**
     * @param Block $block
     * @return bool
     */
    public function onDestroyBlock(Block $block): bool {
        if (!$block->getBreakInfo()->breaksInstantly()) {
            return $this->applyDamage(1);
        }
        return false;
    }

    /**
     * @param Entity $victim
     * @return bool
     */
    public function onAttackEntity(Entity $victim): bool {
        return $this->applyDamage(2);
    }

    /**
     * @return ToolProperties
     */
    public function getProperties(): ToolProperties {
        return $this->properties;
    }
}