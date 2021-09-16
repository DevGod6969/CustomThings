<?php

namespace romainsav\customthings\items\classes;

use romainsav\customthings\items\properties\ItemProperties;
use pocketmine\item\Item;

class ItemClass extends Item {

    /** @var ItemProperties */
    private ItemProperties $properties;

    /**
     * @param ItemProperties $properties
     */
    public function __construct(ItemProperties $properties) {
        $this->properties = $properties;
        parent::__construct($this->getProperties()->getId(), $this->getProperties()->getMeta(), $this->getProperties()->getName());
    }

    /**
     * @return ItemProperties
     */
    public function getProperties(): ItemProperties {
        return $this->properties;
    }
}