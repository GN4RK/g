<?php

namespace YoannLeonard\G\model\Move;

use YoannLeonard\G\model\Entity;
use YoannLeonard\G\model\Move;

use function YoannLeonard\G\printLine;

class Attack extends Move
{
    public function __construct(Entity $entity)
    {
        parent::__construct("attack", "attacking", $entity);
    }

    public function apply(Entity $attackedEntity): void
    {
        parent::apply($attackedEntity);
        $damage = parent::getEntity()->getAttack() - $attackedEntity->getDefense();
        if ($damage <= 0) {
            $damage = 1;
            if ($attackedEntity->isDefending()) {
                printLine($attackedEntity->getName() . ' is defending.');
                $damage = 0;
            }
        }
        $attackedEntity->setHealth($attackedEntity->getHealth() - $damage);
        printLine(parent::getEntity()->getName() . ' attacks ' . $attackedEntity->getName() . ' for ' . $damage . ' damage.');
    }
}