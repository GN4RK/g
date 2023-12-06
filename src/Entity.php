<?php

namespace YoannLeonard\G;

class Entity
{
    private $entityName;
    private $health;
    private $attack;
    private $defense;

    public function __construct(int $health, int $attack, int $defense)
    {
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


}