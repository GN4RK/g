<?php

namespace YoannLeonard\G\Controller;

use YoannLeonard\G\Controller;
use YoannLeonard\G\Entities\Player;
use YoannLeonard\G\Entity;

class FightController extends Controller
{
    public function createFight(Player $player, Entity $entity): Fight
    {
        return new Fight($player, $entity);
    }
}