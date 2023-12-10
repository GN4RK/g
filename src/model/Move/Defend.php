<?php

namespace YoannLeonard\G\model\Move;

use YoannLeonard\G\model\Entity;
use YoannLeonard\G\model\Move;

use function YoannLeonard\G\printLine;

class Defend extends Move
{
    public function __construct(Entity $entity)
    {
        parent::__construct("defend", "defending", $entity);
    }

    public function getBonus(): bool
    {
        parent::getEntity()->setDefense(parent::getEntity()->getBaseDefense() * 2);
        printLine(parent::getEntity()->getName() . '\'s defense is boosted.');
        return true;
    }
}