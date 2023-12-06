<?php

namespace YoannLeonard\G\model\Entity;

use YoannLeonard\G\model\Entity;

class Player extends Entity
{
    private $name;
    private $level;
    private $experience;
    private $gold;


    public function __construct(string $name, int $health, int $attack, int $defense)
    {
        parent::__construct($health, $attack, $defense);
        $this->name = $name;
        $this->level = 1;
        $this->experience = 0;
        $this->gold = 10;

    }

    public function getName(): string
    {
        return $this->name;
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

    public function setName(string $name): void
    {
        $this->name = $name;
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
        return "Player: $this->name, Level: $this->level, Health: ".parent::getHealth().", 
        Attack: ".parent::getAttack().", Defense: ".parent::getDefense().", Experience: $this->experience, Gold: $this->gold";
    }

    public function displayStats(): array
    {
        return [
            "Player    : $this->name",
            "Level     : $this->level",
            "Health    : ".parent::getHealth(),
            "Attack    : ".parent::getAttack(),
            "Defense   : ".parent::getDefense(),
            "Experience: $this->experience",
            "Gold      : $this->gold"
        ];
    }

}