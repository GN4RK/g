<?php

namespace YoannLeonard\G\Model\Entity;

use YoannLeonard\G\Model\Entity;
use YoannLeonard\G\Model\Item\Cheese;
use YoannLeonard\G\Model\Move\Attack;
use YoannLeonard\G\Model\Move\Defend;
use YoannLeonard\G\Model\Move\Flee;

class Rat extends Entity
{
    // constant LOOT is an array of various item with probability to drop
    const LOOT = [
        'gold' => [
            'min' => 1,
            'max' => 10,
            'probability' => 0.5
        ],
        'cheese' => [
            'min' => 1,
            'max' => 1,
            'probability' => 0.5
        ],
        'sword' => [
            'min' => 1,
            'max' => 1,
            'probability' => 0.1
        ],
        'shield' => [
            'min' => 1,
            'max' => 1,
            'probability' => 0.1
        ],
        'potion' => [
            'min' => 1,
            'max' => 1,
            'probability' => 0.1
        ],
    ];

    public function __construct()
    {
        parent::__construct(2, 1, 1);

        parent::setExperience(21);
        parent::setGold(1);

        $this->moveset->addMove(new Attack($this), 0.5);
        $this->moveset->addMove(new Defend($this), 0.4);
        $this->moveset->addMove(new Flee($this), 0.1);

        parent::getInventory()->addItem(new Cheese());

    }

    public function getStats(): array
    {
        return [
            "Entity    : ".parent::getEntityName(),
            "Health    : ".parent::getHealth(),
            "Attack    : ".parent::getAttack(),
            "Defense   : ".parent::getDefense(),
            "Experience: ".parent::getExperience(),
            "Gold      : ".parent::getGold(),
        ];
    }

}