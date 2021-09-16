<?php

namespace romainsav\customthings\items\properties;

use pocketmine\nbt\tag\CompoundTag;
use romainsav\customthings\items\json\BaseJson;

abstract class BaseProperties {

    /** @var string */
    private string $name;

    /** @var string */
    private string $texture;

    /** @var int */
    private int $id;

    /** @var int */
    private int $meta;

    /** @var string */
    protected string $type;

    /** @var CompoundTag */
    private CompoundTag $component;

    public function __construct(BaseJson $baseJson) {
        $this->name = $baseJson->name;
        $this->texture = $baseJson->texture;
        $this->id = $baseJson->id;
        $this->meta = $baseJson->meta ?? 0;
        $this->type = $baseJson->type;

        $this->component = $this->buildComponent();
    }

    public abstract function buildComponent(): CompoundTag;

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getTexture(): string {
        return $this->texture;
    }

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getMeta(): int {
        return $this->meta;
    }

    /**
     * @return string
     */
    public function getType(): string {
        return $this->type;
    }

    /**
     * @return CompoundTag
     */
    public function getComponent(): CompoundTag {
        return $this->component;
    }
}