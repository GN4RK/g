<?php

namespace YoannLeonard\G\model\Move;

use YoannLeonard\G\model\Entity;
use YoannLeonard\G\model\Move;

class Attack extends Move
{
    public function __construct(Entity $entity)
    {
        parent::__construct("attack", "attacking", $entity);
    }
}