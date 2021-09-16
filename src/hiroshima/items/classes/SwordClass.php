<?php

namespace hiroshima\items\classes;

use hiroshima\items\properties\ToolProperties;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\Sword;
use pocketmine\item\ToolTier;

class SwordClass extends Sword {

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

    public function getMaxDurability(): int {
        return $this->getProperties()->getMaxDurability();
    }

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