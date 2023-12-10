<?php

namespace YoannLeonard\G\model\Entity;

use YoannLeonard\G\model\Entity;
use YoannLeonard\G\model\Move\Attack;
use YoannLeonard\G\model\Move\Defend;
use YoannLeonard\G\model\Move\Flee;

class Player extends Entity
{
    private $level;
    private $experience;
    private $gold;


    public function __construct(string $name, int $health, int $attack, int $defense)
    {
        parent::__construct($health, $attack, $defense);
        $this->setName($name);
        $this->level = 1;
        $this->experience = 0;
        $this->gold = 10;

        $this->moveset->addMove(new Attack($this));
        $this->moveset->addMove(new Defend($this));
        $this->moveset->addMove(new Flee($this));

    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function getExperience(): int
    {
        return $this->experience;
    }

    public function getGold(): int
    {
        return $this->gold;
    }

    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    public function setExperience(int $experience): void
    {
        $this->experience = $experience;
    }

    public function setGold(int $gold): void
    {
        $this->gold = $gold;
    }

    public function levelUp(): void
    {
        $this->level++;
        parent::setHealth(parent::getHealth() + 10);
        parent::setAttack(parent::getAttack() + 5);
        parent::setDefense(parent::getDefense() + 5);
    }

    public function __toString(): string
    {
        return "Player: ". $this->getName() .", Level: $this->level, Health: ".parent::getHealth().", 
        Attack: ".parent::getAttack().", Defense: ".parent::getDefense().", Experience: $this->experience, Gold: $this->gold";
    }

    public function getStats(): array
    {
        return [
            "Player    : ". $this->getName(),
            "Level     : $this->level",
            "Health    : ".parent::getHealth() . "/" . parent::getmaxHealth(),
            "Attack    : ".parent::getAttack(),
            "Defense   : ".parent::getBaseDefense() . "+" . parent::getDefense() - parent::getBaseDefense(),
            "Experience: $this->experience",
            "Gold      : $this->gold"
        ];
    }

}