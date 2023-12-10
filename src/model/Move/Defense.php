<?php

namespace YoannLeonard\G\model\Move;

use YoannLeonard\G\model\Entity;
use YoannLeonard\G\model\Move;

class Defense extends Move
{
    public function __construct(Entity $entity)
    {
        parent::__construct("defense", "defending", $entity);
    }

    public function getBonus(): bool
    {
        parent::getEntity()->setDefense(parent::getEntity()->getBaseDefense() * 2);
        return true;
    }
}