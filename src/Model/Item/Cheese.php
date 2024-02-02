<?php

namespace YoannLeonard\G\Model\Item;

use YoannLeonard\G\Model\Entity;
use YoannLeonard\G\Model\Item;
use function YoannLeonard\G\translate;

class Cheese extends Item
{
    private int $healPower;
    public function __construct()
    {
        parent::__construct('🧀 ' . translate('Cheese'), 5);
        $this->setRate(95);
        $this->healPower = 5;
    }

    public function use(Entity $entity): void
    {
        $entity->heal($this->healPower);
    }

    public function getMessageOnUse(Entity $entity): string
    {
        return $entity->getName() . " " . translate('ate') . " " . $this->getName() . " " .
            translate("and") . " " . translate('healed') . $this->healPower . translate("HP");
    }
}