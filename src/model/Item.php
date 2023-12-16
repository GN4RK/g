<?php

namespace YoannLeonard\G\Model;

abstract class Item
{
    private string $name;
    private int $price;

    public function __construct(string $name, int $price)
    {
        $this->setName($name);
        $this->setPrice($price);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    public function __toString() : string
    {
        return $this->getName();
    }

    abstract public function use(Entity $entity): void;
}