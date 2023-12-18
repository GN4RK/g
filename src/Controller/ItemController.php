<?php

namespace YoannLeonard\G\Controller;

use YoannLeonard\G\Game;
use YoannLeonard\G\Model\Item;

class ItemController extends Controller
{
    private static ?ItemController $instance = null;

    public static function getInstance(): ItemController
    {
        if (self::$instance === null) {
            self::$instance = new ItemController(Game::getInstance());
        }
        return self::$instance;
    }

    public function renderItem(Item $item): string
    {
        $itemName = $item->getItemName();
        return file_get_contents(filename: 'src/View/Item/' . $itemName);
    }
}