<?php

namespace YoannLeonard\G\Model;

use function YoannLeonard\G\translate;

abstract class Item
{
    private string $itemName;
    private string $name;
    private int $price;
    private int $rate;
    private bool $removeOnUse = true;
    private bool $isDroppable = true;

    public function __construct(string $name, int $price)
    {
        $this->setName($name);
        $this->setPrice($price);

        $itemClassPath = get_class($this);
        $nameParts = explode('\\', $itemClassPath);
        $this->setItemName(end($nameParts));

        $this->setRate(100);
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

    public function getRate(): int
    {
        return $this->rate;
    }

    public function setRate(int $rate): void
    {
        $this->rate = $rate;
    }

    public function getRemoveOnUse(): bool
    {
        return $this->removeOnUse;
    }

    public function setRemoveOnUse(bool $removeOnUse): void
    {
        $this->removeOnUse = $removeOnUse;
    }

    public function getMessageOnUse(Entity $entity): string
    {
        return $entity->getName() . ' ' . translate('used') . ' ' . $this->getName();
    }

    public function isDroppable(): bool
    {
        return $this->isDroppable;
    }

    public function setIsDroppable(bool $isDroppable): void
    {
        $this->isDroppable = $isDroppable;
    }

    public function __toString() : string
    {
        return $this->getName();
    }

    /**
     * @param Entity $entity The user
     * @return void
     */
    abstract public function use(Entity $entity): void;
}