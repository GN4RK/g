<?php

namespace YoannLeonard\G\Model\Item;

use YoannLeonard\G\Model\Entity;
use YoannLeonard\G\Model\Item;

class RatDancePartyInvitation extends Item
{
    public function __construct()
    {
        parent::__construct('✉️ Rat Dance Party Invitation', 5);
        $this->setRate(10);
        $this->setRemoveOnUse(false);
    }

    public function use(Entity $entity): void
    {}

    public function getMessageOnUse(Entity $entity): string
    {
        return $entity->getName() . " used " . $this->getName() . " and nothing happened";
    }
}