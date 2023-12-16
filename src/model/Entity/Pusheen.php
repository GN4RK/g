<?php

namespace YoannLeonard\G\Model\Entity;

use YoannLeonard\G\Model\Entity;
use YoannLeonard\G\Model\Move\Attack;
use YoannLeonard\G\Model\Move\Defend;
use YoannLeonard\G\Model\Move\Flee;

class Pusheen extends Entity
{

    private $experience;
    private $gold;

    public function __construct()
    {
        parent::__construct(50, 1, 10);

        $this->experience = 5;
        $this->gold = 0;

        $this->moveset->addMove(new Attack($this), 0.5);
        $this->moveset->addMove(new Defend($this), 0.4);
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

    public function getStats(): array
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