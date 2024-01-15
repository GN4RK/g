<?php

namespace YoannLeonard\G\Model\Item;

use YoannLeonard\G\Model\Entity;
use YoannLeonard\G\Model\Item;

class SewerMap extends Item
{
    public function __construct()
    {
        parent::__construct('🗺️ Sewer Map', 8);
        $this->setRate(95);
        $this->setRemoveOnUse(false);
        $this->setIsDroppable(false);
    }

    public function use(Entity $entity): void
    {
        // get entity class
        $entityClass = get_class($entity);

        if ($entityClass === 'YoannLeonard\G\Model\Entity\Player') {
            $entity->setHasAccessToSewer(true);
        }
    }

    public function getMessageOnUse(Entity $entity): string
    {
        return $entity->getName() . " used " . $this->getName() . " and now has access to [blue]the sewer[reset]!";
    }
}