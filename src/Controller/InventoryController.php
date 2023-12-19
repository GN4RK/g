<?php

namespace YoannLeonard\G\Controller;

use YoannLeonard\G\Game;
use YoannLeonard\G\Model\Entity;
use YoannLeonard\G\Model\Inventory;
use YoannLeonard\G\Model\Item;
use function YoannLeonard\G\printLine;

class InventoryController extends Controller
{
    private static ?InventoryController $instance = null;

    /**
     * @return InventoryController
     */
    public static function getInstance(): InventoryController
    {
        if (self::$instance === null) {
            self::$instance = new InventoryController(Game::getInstance());
        }
        return self::$instance;
    }

    /**
     * @param Entity $entity
     * @param Item $item
     */
    public function useItem(Entity $entity, Item $item): void
    {
        $entity->getInventory()->useItem($item, $entity);
        printLine($entity->getName() . " used " . $item->getName());
    }

    /**
     * @param Entity $entity
     * @param Item $item
     */
    public function dropItem(Entity $entity, Item $item): void
    {
        $entity->getInventory()->removeItem($item);
        printLine($entity->getName() . " dropped " . $item->getName());
    }

    /**
     * @param Inventory $inventory
     * @return Item[]
     */
    public function lootItem(Inventory $inventory): array
    {
        $itemController = ItemController::getInstance();
        $lootedItem = [];

        foreach ($inventory->getItems() as $itemName => $quantity) {

            $item = $itemController->createItem($itemName);

            for ($i = 0; $i < $quantity; $i++) {
                if ($item->getRate() >= rand(1, 100)) {
                    $lootedItem[] = $item;
                    printLine("You looted " . $item->getName());
                }
            }
        }

        return $lootedItem;
    }

    public function addItems(array $items, Inventory $inventory): void
    {
        foreach ($items as $item) {
            $inventory->addItem($item);
        }
    }
}