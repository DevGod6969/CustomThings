<?php

namespace romainsav\customthings\items\classes;

use romainsav\customthings\items\properties\ToolProperties;
use pocketmine\item\Sword;

class SwordClass extends Sword {

    /** @var ToolProperties */
    private ToolProperties $properties;

    public function __construct(ToolProperties $properties) {
        $this->properties = $properties;
        parent::__construct($properties->getId(), $properties->getMeta(), $properties->getName(), self::TIER_DIAMOND);
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