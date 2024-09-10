<?php

namespace YoannLeonard\G\Model\Move;

use YoannLeonard\G\Model\Entity;
use YoannLeonard\G\Model\Move;

use function YoannLeonard\G\printLine;
use function YoannLeonard\G\translate;

class Attack extends Move
{
    public function __construct(Entity $entity)
    {
        parent::__construct(translate('attack'), translate("attacking"), $entity);
    }

    public function apply(Entity $entity): void
    {
        parent::apply($entity);
        $damage = parent::getEntity()->getAttack() - $entity->getDefense();
        if ($damage <= 0) {
            $damage = 1;
            if ($entity->isDefending()) {
                printLine($entity->getName() . ' ' . translate('is defending.'));
                $damage = 0;
            }
        }
        $entity->setHealth($entity->getHealth() - $damage);
        printLine(
            parent::getEntity()->getName() . ' ' .
            translate('attacks') . ' ' .
            $entity->getName() . ' ' .
            translate('for') . ' ' . $damage . ' ' .
            translate('damage') . '.');
    }
}