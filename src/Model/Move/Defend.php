<?php

namespace YoannLeonard\G\Model\Move;

use YoannLeonard\G\Model\Entity;
use YoannLeonard\G\Model\Move;

use function YoannLeonard\G\printLine;
use function YoannLeonard\G\translate;

class Defend extends Move
{
    public function __construct(Entity $entity)
    {
        parent::__construct("defend", "defending", $entity);
    }

    public function getBonus(): bool
    {
        parent::getEntity()->setDefense(parent::getEntity()->getBaseDefense() * 2);
        $message = translate("%entity%'s defense is boosted");
        $message = str_replace('%entity%', parent::getEntity()->getName(), $message);
        printLine($message);
        return true;
    }
}