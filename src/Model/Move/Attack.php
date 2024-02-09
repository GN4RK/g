<?php

namespace YoannLeonard\G\Model\Move;

use YoannLeonard\G\Model\Entity;
use YoannLeonard\G\Model\Move;

use function YoannLeonard\G\printLine;

class Attack extends Move
{
    public function __construct(Entity $entity)
    {
        parent::__construct("attack", "attacking", $entity);
    }

    public function apply(Entity $entity): void
    {
        parent::apply($entity);
        $damage = parent::getEntity()->getAttack() - $entity->getDefense();
        if ($damage <= 0) {
            $damage = 1;
            if ($entity->isDefending()) {
                printLine($entity->getName() . ' is defending.');
                $damage = 0;
            }
        }
        $entity->setHealth($entity->getHealth() - $damage);
        printLine(parent::getEntity()->getName() . ' attacks ' . $entity->getName() . ' for ' . $damage . ' damage.');
    }
}