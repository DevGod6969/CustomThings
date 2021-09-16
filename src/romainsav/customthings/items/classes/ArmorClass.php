<?php

namespace romainsav\customthings\items\classes;

use pocketmine\item\Armor;
use pocketmine\item\ArmorTypeInfo;
use pocketmine\item\ItemIdentifier;
use romainsav\customthings\items\properties\ArmorProperties;

class ArmorClass extends Armor {

    /** @var ArmorProperties */
    private ArmorProperties $properties;

    public function __construct(ArmorProperties $properties) {
        $this->properties = $properties;

        parent::__construct(
            new ItemIdentifier($properties->getId(), $properties->getMeta()),
            $properties->getName(),
            new ArmorTypeInfo($properties->getDefensePoint(), $properties->getMaxDurability(), $properties->getSlot())
        );
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