<?php

namespace YoannLeonard\G\Model;

class Moveset
{
    private array $moves;

    public function __construct()
    {
        $this->moves = [];
    }

    public function getMoves(): array
    {
        return $this->moves;
    }

    public function setMoves(array $moves): void
    {
        $this->moves = $moves;
    }

    public function addMove(Move $move, float $probability = 0): void
    {
        $this->moves[] = ['move' => $move, 'probability' => $probability];
    }

    public function removeMove(Move $move): void
    {
        foreach ($this->moves as $key => $value) {
            if ($value[0] === $move) {
                unset($this->moves[$key]);
            }
        }
    }
}