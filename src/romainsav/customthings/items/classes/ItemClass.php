<?php

namespace romainsav\customthings\items\classes;

use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;
use romainsav\customthings\items\properties\ItemProperties;

class ItemClass extends Item {

    /** @var ItemProperties */
    private ItemProperties $properties;

    /**
     * @param ItemProperties $properties
     */
    public function __construct(ItemProperties $properties) {
        $this->properties =  $properties;

        parent::__construct(
            new ItemIdentifier($this->getProperties()->getId(), $this->getProperties()->getMeta()),
            $this->getProperties()->getName()
        );
    }

    /**
     * @return ItemProperties
     */
    public function getProperties(): ItemProperties {
        return $this->properties;
    }
}