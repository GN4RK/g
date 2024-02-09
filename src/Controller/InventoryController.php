<?php

namespace YoannLeonard\G\Controller;

use YoannLeonard\G\Game;
use YoannLeonard\G\Model\Entity;
use YoannLeonard\G\Model\Inventory;
use YoannLeonard\G\Model\Item;
use function YoannLeonard\G\printLine;
use function YoannLeonard\G\translate;

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
    public function useItem(Entity $entity, Item $item): string
    {
        return $entity->getInventory()->useItem($item, $entity);
    }

    /**
     * @param Entity $entity
     * @param Item $item
     */
    public function dropItem(Entity $entity, Item $item): void
    {
        if (!$item->isDroppable()) {
            printLine(translate("You can't drop this item"));
            return;
        }
        $entity->getInventory()->removeItem($item);
        printLine($entity->getName() . " " . translate("dropped") . " " . $item->getName());
    }

    /**
     * @param Inventory $inventory
     * @return Item[]
     */
    public function lootItem(Inventory $inventory): array
    {
        $itemController = ItemController::getInstance();
        $lootedItem = [];

        foreach ($inventory->getItems() as $item) {
            if ($item->getRate() >= rand(1, 100)) {
                $lootedItem[] = $item;
                printLine(translate("You looted") . " " . $item->getName());
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