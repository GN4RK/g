<?php

namespace YoannLeonard\G\model\Move;

use YoannLeonard\G\model\Entity;
use YoannLeonard\G\model\Move;

class Flee extends Move
{
    public function __construct(Entity $entity)
    {
        parent::__construct("flee", "fleeing", $entity);
    }
}