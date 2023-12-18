<?php

namespace YoannLeonard\G\Model;

use PHPUnit\Framework\Constraint\IsEmpty;

class Inventory
{
    private $items;

    public function __construct()
    {
        $this->items = [];
    }

    public function addItem(Item $item): void
    {
        $this->items[] = $item;
    }

    public function removeItem(Item $item): void
    {
        $key = array_search($item, $this->items);
        unset($this->items[$key]);
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function __toString() : string
    {
        $string = "";
        foreach ($this->items as $item) {
            $string .= $item->getName() . " ";
        }
        return $string;
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }
}