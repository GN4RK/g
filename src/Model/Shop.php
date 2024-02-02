<?php

namespace YoannLeonard\G\Model;

use YoannLeonard\G\Model\Item;
use function YoannLeonard\G\translate;

// singleton class

class Shop
{
    private static ?Shop $instance = null;
    private array $items;

    private function __construct()
    {
        $this->items = [];
    }

    public static function getInstance(): Shop
    {
        if (self::$instance === null) {
            self::$instance = new Shop();
        }
        return self::$instance;
    }

    public static function setInstance(Shop $instance): void
    {
        self::$instance = $instance;
    }

    public function addItem(Item $item): void
    {
        $this->items[] = $item;
    }

    public function removeItem(Item $item): void
    {
        $key = array_search($item, $this->items);
        unset($this->items[$key]);
        $this->reorganize();
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function __toString() : string
    {
        $string = "";
        foreach ($this->items as $item) {
            $string .= $item->getName() . "\n";
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

    function getMenuActions(): array
    {
        $actions = [];
        $actions[] = translate('Buy');
        $actions[] = translate('Sell');
        $actions[] = translate('Talk');
        $actions[] = translate('Leave');

        return $actions;
    }
}