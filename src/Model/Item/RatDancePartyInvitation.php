<?php

namespace YoannLeonard\G\Model\Item;

use YoannLeonard\G\Model\Entity;
use YoannLeonard\G\Model\Item;
use function YoannLeonard\G\translate;

class RatDancePartyInvitation extends Item
{
    public function __construct()
    {
        parent::__construct('✉️ ' . translate('Rat Dance Party Invitation'), 5);
        $this->setRate(50);
        $this->setRemoveOnUse(false);
    }

    public function use(Entity $entity): void
    {}

    public function getMessageOnUse(Entity $entity): string
    {
        return $entity->getName() . ' ' . translate('used') . ' ' . $this->getName() . ' ' .
            translate('and nothing happened');
    }
}