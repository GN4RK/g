<?php

namespace YoannLeonard\G\model;

abstract class Move 
{
    private string $name;
    private string $state;
    private Entity $entity;

    public function __construct(string $name, string $state, Entity $entity)
    {
        $this->name = $name;
        $this->state = $state;
        $this->entity = $entity;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->$name = $name;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): void
    {
        $this->$state = $state;
    }

    public function getBonus(): bool
    {
        return false;
    }

    public function getEntity(): Entity
    {
        return $this->entity;
    }

    public function apply(Entity $entity): void {}

}