<?php

namespace YoannLeonard\G;

class Player extends Entity
{
    private string $name;
    private int $health;
    private int $strength;
    private int $defense;
    private int $speed;
    private int $luck;

    public function __construct(string $name, int $health, int $strength, int $defense, int $speed, int $luck)
    {
        $this->name = $name;
        $this->health = $health;
        $this->strength = $strength;
        $this->defense = $defense;
        $this->speed = $speed;
        $this->luck = $luck;
    }

}