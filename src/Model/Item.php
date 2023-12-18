<?php

namespace YoannLeonard\G\Model;

abstract class Item
{
    private string $itemName;
    private string $name;
    private int $price;

    public function __construct(string $name, int $price)
    {
        $this->setName($name);
        $this->setPrice($price);

        $itemClassPath = get_class($this);
        $nameParts = explode('\\', $itemClassPath);
        $this->setItemName(end($nameParts));
    }

    public function getItemName(): string
    {
        return $this->itemName;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setItemName(string $itemName): void
    {
        $this->itemName = $itemName;
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

    /**
     * @param Entity $entity The user
     * @return string[]
     */
    abstract public function use(Entity $entity): void;
}