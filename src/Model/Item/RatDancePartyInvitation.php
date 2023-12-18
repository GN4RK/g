<?php

namespace YoannLeonard\G\Model\Item;

use YoannLeonard\G\Model\Entity;
use YoannLeonard\G\Model\Item;
use function YoannLeonard\G\printLines;

class RatDancePartyInvitation extends Item
{
    public function __construct()
    {
        parent::__construct('✉️ Rat Dance Party Invitation', 5);
    }

    public function use(Entity $entity): array
    {
        return [
            'Rat Dance Party Invitation',
        ];

    }
}