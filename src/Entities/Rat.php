<?php

namespace YoannLeonard\G\Entities;

use YoannLeonard\G\Entity;

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

    private int $health;
    private int $attack;
    private int $defense;
    private int $experience;
    private int $gold;



    public function __construct()
    {
        parent::__construct();

        $this->health = 10;
        $this->attack = 5;
        $this->defense = 5;
        $this->experience = 5;
        $this->gold = 0;


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

    public function displayStats(): array
    {
        return [
            "Entity    : ".parent::getEntityName(),
            "Health    : $this->health",
            "Attack    : $this->attack",
            "Defense   : $this->defense",
            "Experience: $this->experience",
            "Gold      : $this->gold"
        ];
    }

}