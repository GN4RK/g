<?php

namespace YoannLeonard\G;

abstract class Controller
{
    private Game $game;

    public function __construct(Game $game)
    {
        $this->game = Game::getInstance();
    }

    public function getGame(): Game
    {
        return $this->game;
    }
}