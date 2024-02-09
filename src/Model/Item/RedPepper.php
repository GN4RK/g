<?php

namespace YoannLeonard\G\Model\Item;

use YoannLeonard\G\Model\Entity;
use YoannLeonard\G\Model\Item;
use function YoannLeonard\G\translate;

class RedPepper extends Item
{
    public function __construct()
    {
        parent::__construct('🌶 ' . translate('Red Pepper'), 1);
        $this->setRate(95);
    }

    public function use(Entity $entity): void
    {
        // boost attack
        $entity->setAttack($entity->getAttack() + 1);
    }

    public function getMessageOnUse(Entity $entity): string
    {
        return $entity->getName() . ' ' . translate('ate') . ' ' . $this->getName() . ' ' .
            translate('and gained 1 bonus attack point');
    }
}