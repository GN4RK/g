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

    public function addItem(Item $item, int $quantity = 1): void
    {
        $itemName = $item->getItemName();

        if (array_key_exists($itemName, $this->items)) {
            $this->items[$itemName] += $quantity;
        } else {
            $this->items[$itemName] = $quantity;
        }
    }

    public function removeItem(Item $item): void
    {
        $key = array_search($item, $this->items);
        unset($this->items[$key]);
        $this->reorganize();
    }

    public function useItem(Item $item, Entity $entity): void
    {
        $item->use($entity);
        $this->removeItem($item);
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

    public function reorganize(): void
    {
        $this->items = array_values($this->items);
    }
}