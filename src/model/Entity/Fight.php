<?php

namespace YoannLeonard\G\model\Entity;

use YoannLeonard\G\model\Entity;
use YoannLeonard\G\model\Entity\Player;

class Fight
{
    private Player $player;
    private Entity $entity;
    private int $id;
    private int $turn;

    public function __construct(Player $player, Entity $entity)
    {
        $this->player = $player;
        $this->entity = $entity;
        $this->id = rand(1, 1000000);
        $this->turn = 1;
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function getEntity(): Entity
    {
        return $this->entity;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTurn(): int
    {
        return $this->turn;
    }

    public function incrementTurn(): void
    {
        $this->turn++;
    }

    public function __toString(): string
    {
        return 'Fight ' . $this->id . ' : ' . $this->player->getName() . ' vs ' . $this->entity->getEntityName();
    }
}