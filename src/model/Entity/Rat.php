<?php

namespace YoannLeonard\G\model\Entity;

use YoannLeonard\G\model\Entity;
use YoannLeonard\G\model\Move\Attack;
use YoannLeonard\G\model\Move\Defense;
use YoannLeonard\G\model\Move\Flee;

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

    private $experience;
    private $gold;

    public function __construct()
    {
        parent::__construct(10, 5, 5);

        $this->experience = 5;
        $this->gold = 0;

        $this->moveset->addMove(new Attack($this), 0.5);
        $this->moveset->addMove(new Defense($this), 0.4);
        $this->moveset->addMove(new Flee($this), 0.1);

    }

    public function getExperience(): int
    {
        return $this->experience;
    }

    public function getGold(): int
    {
        return $this->gold;
    }

    public function setExperience(int $experience): void
    {
        $this->experience = $experience;
    }

    public function setGold(int $gold): void
    {
        $this->gold = $gold;
    }

    public function displayStats(): array
    {
        return [
            "Entity    : ".parent::getEntityName(),
            "Health    : ".parent::getHealth(),
            "Attack    : ".parent::getAttack(),
            "Defense   : ".parent::getDefense(),
            "Experience: $this->experience",
            "Gold      : $this->gold"
        ];
    }

}