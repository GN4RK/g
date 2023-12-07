<?php

namespace YoannLeonard\G\model;

class Entity
{
    private $entityName;
    private $state;
    private $status;
    private $health;
    private $attack;
    private $defense;

    public const MOVESET = [
        'attack' => 0.5,
        'defense' => 0.4,
        'flee' => 0.1
    ];

    public const STATE = [
        'ready' => 1,
        'attacking' => 2,
        'defending' => 3,
        'fleeing' => 4
    ];

    public const STATUS = [
        'normal' => 1,
        'poisoned' => 2,
        'burned' => 3,
        'paralyzed' => 4,
        'asleep' => 5,
        'frozen' => 6,
        'confused' => 7,
        'flee' => 8,
        'dead' => 9
    ];

    public function __construct(int $health, int $attack, int $defense)
    {
        $this->status = 'normal';
        $this->state = 'ready';
        $this->health = $health;
        $this->attack = $attack;
        $this->defense = $defense;

        $entityClassPath = get_class($this);
        $nameParts = explode('\\', $entityClassPath);
        $this->entityName = end($nameParts);
    }

    public function getEntityName(): string
    {
        return $this->entityName;
    }

    public function isAlive(): bool
    {
        return $this->health > 0;
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

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getStatus(): string
    {
        if ($this->status === null) {
            return 'normal';
        }
        return $this->status;
    }

    public function chooseAction(): string
    {
        $randomNumber = mt_rand() / mt_getrandmax();

        $cumulativeProbability = 0;
        foreach ($this::MOVESET as $move => $probability) {
            $cumulativeProbability += $probability;
            if ($randomNumber <= $cumulativeProbability) {
                return $move;
            }
        }

        return array_key_first($this::MOVESET);
    }


}