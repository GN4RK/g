<?php

namespace YoannLeonard\G\Model\Move;

use YoannLeonard\G\Model\Entity;
use YoannLeonard\G\Model\Move;

class Flee extends Move
{
    public function __construct(Entity $entity)
    {
        parent::__construct("flee", "fleeing", $entity);
    }
}