<?php

namespace YoannLeonard\G\Entities;

use YoannLeonard\G\Entity;

class Player extends Entity
{
    private string $name;
    private int $level;
    private int $health;
    private int $attack;
    private int $defense;
    private int $experience;
    private int $gold;


    public function __construct(string $name, int $health, int $attack, int $defense)
    {
        parent::__construct();

        $this->name = $name;
        $this->health = $health;
        $this->attack = $attack;
        $this->defense = $defense;
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

    public function getHealth(): int
    {
        return $this->health;
    }

    public function getAttack(): int
    {
        return $this->attack;
    }

    public function getDefense(): int
    {
        return $this->defense;
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

    public function setHealth(int $health): void
    {
        $this->health = $health;
    }

    public function setAttack(int $attack): void
    {
        $this->attack = $attack;
    }

    public function setDefense(int $defense): void
    {
        $this->defense = $defense;
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
        $this->health += 10;
        $this->attack += 5;
        $this->defense += 5;
    }

    public function __toString(): string
    {
        return "Player: $this->name, Level: $this->level, Health: $this->health, Attack: $this->attack, Defense: $this->defense";
    }

    public function displayStats(): array
    {
        return [
            "Player    : $this->name",
            "Level     : $this->level",
            "Health    : $this->health",
            "Attack    : $this->attack",
            "Defense   : $this->defense",
            "Experience: $this->experience",
            "Gold      : $this->gold"
        ];
    }

}