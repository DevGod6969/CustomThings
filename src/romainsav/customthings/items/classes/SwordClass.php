<?php

namespace romainsav\customthings\items\classes;

use pocketmine\item\ItemIdentifier;
use pocketmine\item\Sword;
use pocketmine\item\ToolTier;
use romainsav\customthings\items\properties\ToolProperties;

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