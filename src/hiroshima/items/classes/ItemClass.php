<?php

namespace hiroshima\items\classes;

use hiroshima\items\properties\ItemProperties;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;

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