<?php

namespace YoannLeonard\G\Controller;

use YoannLeonard\G\Game;
use YoannLeonard\G\Model\Entity;
use YoannLeonard\G\Model\Item;
use function YoannLeonard\G\printLine;

class InventoryController extends Controller
{
    private static ?InventoryController $instance = null;

    public static function getInstance(): InventoryController
    {
        if (self::$instance === null) {
            self::$instance = new InventoryController(Game::getInstance());
        }
        return self::$instance;
    }

    public function useItem(Entity $entity, Item $item): void
    {
        $entity->getInventory()->useItem($item, $entity);
        printLine($entity->getName() . " used " . $item->getName());
    }

    public function dropItem(Entity $entity, Item $item): void
    {
        $entity->getInventory()->removeItem($item);
        printLine($entity->getName() . " dropped " . $item->getName());
    }



}