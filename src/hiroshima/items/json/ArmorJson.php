<?php

namespace hiroshima\items\json;

class ArmorJson extends BaseJson {

    /**
     * @var int
     * @required
     */
    public int $max_durability;

    /**
     * @var int
     * @required
     */
    public int $defense_point;

    /**
     * @var int
     * @required
     */
    public int $protection;
}