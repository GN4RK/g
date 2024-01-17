<?php

namespace YoannLeonard\G\Model\Item;

use YoannLeonard\G\Model\Entity;
use YoannLeonard\G\Model\Item;

class RedPepper extends Item
{
    public function __construct()
    {
        parent::__construct('🌶 Red Pepper', 1);
        $this->setRate(95);
    }

    public function use(Entity $entity): void
    {
        // boost attack
        $entity->setAttack($entity->getAttack() + 1);
    }

    public function getMessageOnUse(Entity $entity): string
    {
        return $entity->getName() . " ate " . $this->getName() . " and gained 1 bonus attack point";
    }
}