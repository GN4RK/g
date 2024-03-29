<?php

namespace YoannLeonard\G\Controller;

use YoannLeonard\G\Game;

abstract class Controller
{
    private Game $game;

    public function __construct()
    {
        $this->game = Game::getInstance();
    }

    public function getGame(): Game
    {
        return $this->game;
    }
}