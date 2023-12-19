<?php

namespace YoannLeonard\G\Model\Item;

use YoannLeonard\G\Model\Entity;
use YoannLeonard\G\Model\Item;

class Cheese extends Item
{
    public function __construct()
    {
        parent::__construct('🧀 Cheese', 5);
        $this->setRate(95);
    }

    public function use(Entity $entity): void
    {
        $entity->heal(5);
    }
}