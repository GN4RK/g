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


    public function __construct(string $name, int $health, int $attack, int $defense)
    {
        parent::__construct();

        $this->name = $name;
        $this->health = $health;
        $this->attack = $attack;
        $this->defense = $defense;
        $this->level = 1;

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

    public function __toString(): string
    {
        return "Player: $this->name, Level: $this->level, Health: $this->health, Attack: $this->attack, Defense: $this->defense";
    }

}