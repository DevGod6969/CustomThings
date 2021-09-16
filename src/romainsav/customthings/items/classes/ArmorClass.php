<?php

namespace romainsav\customthings\items\classes;

use romainsav\customthings\items\properties\ArmorProperties;
use pocketmine\item\Armor;

class ArmorClass extends Armor {

    /** @var ArmorProperties */
    private ArmorProperties $properties;

    public function __construct(ArmorProperties $properties) {
        $this->properties = $properties;
        parent::__construct($properties->getId(), $properties->getMeta(), $properties->getName());
    }

    public function getMaxStackSize(): int {
        return 1;
    }

    public function getMaxDurability(): int {
        return $this->getProperties()->getMaxDurability();
    }

    public function getDefensePoints(): int {
        return $this->getProperties()->getDefensePoint();
    }

    /**
     * @return ArmorProperties
     */
    public function getProperties(): ArmorProperties {
        return $this->properties;
    }
}