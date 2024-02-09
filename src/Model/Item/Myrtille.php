<?php

namespace YoannLeonard\G\Model\Item;

use YoannLeonard\G\Model\Entity;
use YoannLeonard\G\Model\Item;
use function YoannLeonard\G\translate;

class Myrtille extends Item
{
    public function __construct()
    {
        parent::__construct('🍇 ' . translate('Myrtille'), 1);
        $this->setRate(95);
    }

    public function use(Entity $entity): void
    {
        // boost defense
        $entity->setDefense($entity->getDefense() + 1);
    }

    public function getMessageOnUse(Entity $entity): string
    {
        return $entity->getName() . " " . translate('ate') . " " . $this->getName() .
            " " . translate('and') . ' ' . translate("gained 1 bonus defense point");
    }
}