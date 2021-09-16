<?php

namespace hiroshima\items\json;

class BaseJson {

    /**
     * @var string
     * @required
     */
    public string $name;

    /**
     * @var string
     * @required
     */
    public string $texture;

    /**
     * @var int
     * @required
     */
    public int $id;

    /**
     * @var int
     */
    public int $meta;

    /**
     * @var string
     * @required
     */
    public string $type;
}