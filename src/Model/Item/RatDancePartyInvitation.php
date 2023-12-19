<?php

namespace YoannLeonard\G\Model\Item;

use YoannLeonard\G\Model\Entity;
use YoannLeonard\G\Model\Item;

class RatDancePartyInvitation extends Item
{
    public function __construct()
    {
        parent::__construct('✉️  Rat Dance Party Invitation', 5);
        $this->setRate(10);
    }

    public function use(Entity $entity): void
    {}
}