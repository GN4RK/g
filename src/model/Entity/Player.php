<?php

namespace YoannLeonard\G\Model\Entity;

use YoannLeonard\G\Model\Entity;
use YoannLeonard\G\Model\Move\Attack;
use YoannLeonard\G\Model\Move\Defend;
use YoannLeonard\G\Model\Move\Flee;

class Player extends Entity
{
    private $level;

    public function __construct(string $name, int $health, int $attack, int $defense)
    {
        parent::__construct($health, $attack, $defense);
        $this->setName($name);
        $this->level = 1;
        parent::setExperience(0);
        parent::setGold(10);

        $this->moveset->addMove(new Attack($this));
        $this->moveset->addMove(new Defend($this));
        $this->moveset->addMove(new Flee($this));

    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    public function levelUp(): void
    {
        $this->level++;
        parent::setmaxHealth(parent::getmaxHealth() + 2);
        parent::setBaseAttack(parent::getBaseAttack() + 1);
        parent::setBaseDefense(parent::getBaseDefense() + 1);
        parent::fullHeal();
    }

    // return true if player level up
    public function addExperience(int $experience): bool
    {
        parent::setExperience(parent::getExperience() + $experience);
        if (parent::getExperience() >= 20) {
            $this->levelUp();
            parent::setExperience(parent::getExperience() - 20);
            return true;
        }
        return false;
    }

    public function addGold(int $gold): void
    {
        parent::setGold(parent::getGold() + $gold);
    }

    public function __toString(): string
    {
        return "Player: ". $this->getName() .", Level: $this->level, Health: ".parent::getHealth().", 
        Attack: ". parent::getAttack() .", Defense: ". parent::getDefense() .", Experience: ". parent::getExperience() .", Gold: ". parent::getGold();
    }

    public function getStats(): array
    {
        return [
            "Player    : ". $this->getName(),
            "Level     : $this->level",
            "Health    : ".parent::getHealth() . "/" . parent::getmaxHealth(),
            "Attack    : ".parent::getAttack() . "+" . parent::getAttack() - parent::getBaseAttack(),
            "Defense   : ".parent::getBaseDefense() . "+" . parent::getDefense() - parent::getBaseDefense(),
            "Status    : ".parent::getStatus(),
            "Experience: ".parent::getExperience(),
            "Gold      : ".parent::getGold(),
        ];
    }
}