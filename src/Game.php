<?php

namespace YoannLeonard\G;

use Exception;
use YoannLeonard\G\Controller\EntityController;
use YoannLeonard\G\Controller\FightController;
use YoannLeonard\G\Controller\InventoryController;
use YoannLeonard\G\Controller\ItemController;
use YoannLeonard\G\Model\Entity;
use YoannLeonard\G\Model\Entity\Player;
use YoannLeonard\G\Model\Entity\Rat;
use YoannLeonard\G\Model\Item\Myrtille;
use YoannLeonard\G\Model\Item\RedPepper;
use YoannLeonard\G\Model\Item\SewerMap;
use YoannLeonard\G\Model\Shop;

// singleton class
class Game
{
    private static ?Game $instance = null;
    private ?Player $player = null;
    private function __construct()
    {
    }

    public static function getInstance(): Game
    {
        if (self::$instance === null) {
            self::$instance = new Game();
        }
        return self::$instance;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(Player $player): void
    {
        $this->player = $player;
    }

    public function createPlayer($playerName): Player
    {
        if ($playerName == 'test') {
            return new Player($playerName, 10, 5, 5);
        }
        $health = readIntInput(translate('enter your health'), 10, 18);
        $attack = readIntInput(translate('enter your attack'), 1, 9);
        $defense = readIntInput(translate('enter your defense'), 1, 9);

        $player = new Player($playerName, $health, $attack, $defense);

        if (!$this->validateStats($player)) {
            return $this->createPlayer($playerName);
        }

        return $player;
    }

    public function validateStats(Player $player): bool
    {
        $health = $player->getHealth();
        $attack = $player->getAttack();
        $defense = $player->getDefense();

        if ($health < 10) {
            printLine(translate('health<10'));
            return false;
        }

        if ($attack < 1) {
            printLine(translate('attack<1'));
            return false;
        }

        if ($defense < 1) {
            printLine(translate('defense<1'));
            return false;
        }

        if ($health + $attack + $defense > 20) {
            printLine(translate('points>20'));
            return false;
        }

        return true;

    }

    /**
     * @throws Exception
     */
    public function start(): void
    {
        clearScreen();
        printLineWithBreak(translate("welcome"));

        $choice = $this->askChoice([
            translate('start a new game'),
            translate('load a saved game')
        ]);

        if ($choice == 1) {
            $this->newGame();
        } else {
            if (!$this->loadGame()) {
                $this->newGame();
            }
        }

        $this->mainLoop();
    }

    /**
     * @throws Exception
     */
    function mainLoop(): void
    {
        $fightController = FightController::getInstance();
        $entityController = EntityController::getInstance();

        while ($this->getPlayer()->isAlive()) {

            $menuAction = $this->getPlayer()->getMenuActions();

            $choice = $this->askChoice($menuAction);

            switch ($menuAction[$choice-1]) {
                case translate('Check stats'):
                    printLines($this->getPlayer()->getStats());
                    break;
                case translate('Check inventory'):
                    $this->inventory();
                    break;
                case translate('Save and quit'):
                    $this->save();
                    exit;
                case translate('Go to the shop'):
                    $this->shop();
                    break;
                case translate('Go to the sewer'):
                    printLineWithBreak(translate('Searching for combat...'));
                    
                    $enemy = $this->randomEnemy();
                    printLineWithBreak(translate('You found a ') . $enemy->getEntityName() . '!');
                    printLine($entityController->renderEntity($enemy));

                    $choiceEncounter = $this->askChoice([
                        translate('Fight'),
                        translate('Run away')
                    ]);

                    if ($choiceEncounter == 2) {
                        printLine(translate('You ran away!'));
                        break;
                    }

                    $fight = $fightController->createFight($this->getPlayer(), $enemy);
                    printLineWithBreak(
                        translate('A fight has started between ') .
                        $fight->getPlayer()->getName() .
                        ' ' . translate('and') . ' ' .
                        $fight->getEntity()->getEntityName() .
                        '!'
                    );
                    $fightController->startFight($fight);
                    break;
                default:
                    printLine(translate('Invalid choice'));
                    break;
            }
        }

        printLineWithBreak(translate('You died'));
        printLineWithBreak(translate('Game over'));
    }

    function newGame(): void
    {
        $playerName = readInput(translate('enter your name'));
        printLinesWithBreak([
            translate("Hello") . " " . $playerName,
            translate("You have 20 stat points to distribute between health, attack and defense."),
            translate("Health can't be less than 10"),
            translate("You can't have less than 1 point in any stat.")
        ]);

        $this->setPlayer($this->createPlayer($playerName));

        if (!$this->validateStats($this->getPlayer())) {
            main();
        }

        printLineWithBreak(translate('Your stats are valid'));
        printLines($this->getPlayer()->getStats());

        // creating shop
        $shop = Shop::getInstance();
        $shop->addItem(new SewerMap());
        $shop->addItem(new RedPepper());
        $shop->addItem(new Myrtille());
    }

    function save(): void
    {
        $player = $this->getPlayer();
        $shop = Shop::getInstance();

        $data = [
            'player' => $player,
            'shop' => $shop
        ];

        $serializedData = serialize($data);

        $fileName = readInput(translate('Enter a name for your save: '));
        // check if folder exists
        if (!file_exists('saves')) {
            mkdir('saves');
        }
        file_put_contents('saves/' . $fileName . '.sav', $serializedData);
        printLineWithBreak(translate('Game saved'));
    }

    /**
     * @throws Exception
     */
    function loadGame(): bool
    {
        // check if folder exists
        if (!file_exists('saves')) {
            mkdir('saves');
        }
        $saves = scandir('saves');
        if (count($saves) == 2) {
            printLineWithBreak(translate('No save found'));
            return false;
        }

        $choices = [];
        foreach ($saves as $save) {
            if ($save == '.' || $save == '..') {
                continue;
            }
            $choices[] = $save;
        }

        $choice = $this->askChoice($choices, 1, count($choices), translate('Choose a save:'));
        $fileName = $choices[$choice - 1];

        $serializedData = file_get_contents('saves/' . $fileName);
        $data = unserialize($serializedData);
        $this->setPlayer($data['player']);
        Shop::setInstance($data['shop']);
        printLineWithBreak(translate('Player loaded'));
        printLines($this->getPlayer()->getStats());
        return true;
    }

    /**
     * @throws Exception
     */
    function askChoice(array $choices, int $min = 1, int $max = 100, string $prompt = null): int
    {
        if ($max == 100) {
            $max = count($choices);
        }

        if ($min < 1) {
            $min = 1;
        }

        if ($max > count($choices)) {
            $max = count($choices);
        }
        
        if ($min > $max) {
            throw new Exception('Min can\'t be greater than max');
        }

        // add number to choices
        for ($i = 0; $i < count($choices); $i++) {
            $choices[$i] = ($i + 1) . ': ' . $choices[$i];
        }

        if ($prompt == null) {
            $prompt = translate('What do you want to do?');
        }

        printLineWithBreak();
        printLines(array_merge(["[green]" . $prompt . "[reset]"], $choices));
        $choice = readIntInput(translate('> Your choice: '), $min, $max);
        printLineWithBreak();
        
        return $choice;
    }

    function randomEnemy(): Entity
    {
        $enemies = [
            new Rat()
        ];

        $randomIndex = rand(0, count($enemies) - 1);
        return $enemies[$randomIndex];

    }

    /**
     * @throws Exception
     */
    function inventory(): void
    {
        if ($this->getPlayer()->getInventory()->isEmpty()) {
            printLine(translate('Your inventory is empty.'));
            return;
        }

        $itemController = ItemController::getInstance();
        $inventoryController = InventoryController::getInstance();

        $items = $this->getPlayer()->getInventory()->getItems();

        // get item class names
        $itemClassNames = [];
        foreach ($items as $item) {
            $itemClassNames[] = get_class($item);
        }

        // unique
        $itemClassNames = array_unique($itemClassNames);

        // get item quantity
        $itemQuantities = [];
        foreach ($itemClassNames as $itemClassName) {
            $itemQuantities[$itemClassName] = 0;
        }

        foreach ($items as $item) {
            $itemQuantities[get_class($item)]++;
        }

        // display items
        $displayedItems = [];
        $firstAndQuantity = [];
        foreach ($itemQuantities as $itemClassName => $itemQuantity) {
            // get first item of this class
            $firstItem = $items[array_search($itemClassName, $itemClassNames)];
            $firstAndQuantity[] = [$firstItem, $itemQuantity];
        }

        foreach ($firstAndQuantity as $itemAndQuantity) {
            $displayedItems[] = $itemAndQuantity[0]->getName() . ' x' . $itemAndQuantity[1];
        }

        $choice = $this->askChoice(
            array_merge($displayedItems, [translate('Back')]),
            1,
            count($firstAndQuantity) + 1,
            translate('Choose an item:')
        );

        if ($choice == count($firstAndQuantity) + 1) {
            return;
        }

        $chosenItem = $firstAndQuantity[$choice - 1][0];

        $choice = $this->askChoice([
            translate('Use'),
            translate('View'),
            translate('Drop'),
            translate('Back')
        ]);

        switch ($choice) {
            case 1:
                printLine(
                    $inventoryController->useItem($this->getPlayer(), $chosenItem)
                );
                break;
            case 2:
                printLine($itemController->renderItem($chosenItem));
                break;
            case 3:
                $inventoryController->dropItem($this->getPlayer(), $chosenItem);
                break;
            case 4:
                return;
            default:
                printLine(translate('Invalid choice'));
                break;
        }
    }

    /**
     * @throws Exception
     */
    function shop(): void
    {
        $shop = Shop::getInstance();
        $menuAction = $shop->getMenuActions();
        $choice = 1;

        while ($menuAction[$choice-1] != translate('Back')) {
            $choice = $this->askChoice($menuAction);
            switch ($menuAction[$choice-1]) {
                case translate('Buy'):
                    $this->buy();
                    break;
                case translate('Sell'):
                    $this->sell();
                    break;
                case translate('Talk'):
                    printLine(translate('The shopkeeper says: "Hello adventurer!"'));
                    break;
                case translate('Leave'):
                    return;
                default:
                    printLine(translate('Invalid choice'));
                    break;
            }
        }
    }

    /**
     * @throws Exception
     */
    function buy(): void
    {
        $shop = Shop::getInstance();

        if ($shop->isEmpty()) {
            printLine(translate('The shop is empty.'));
            return;
        }

        $items = $shop->getItems();
        $choices = [];
        foreach ($items as $item) {
            $choices[] = $item->getName() . ' (' . $item->getPrice() . ' ' . translate('gold') . ')';
        }
        $choices[] = translate('Back');
        $choice = $this->askChoice(
            $choices,
            1,
            100,
            translate('You have ') .
                $this->getPlayer()->getGold() .
                ' ' . translate('gold') . '. '. translate('Choose an item:')
        );
        if ($choice == count($choices)) {
            return;
        }
        $chosenItem = $items[$choice - 1];
        if ($this->getPlayer()->getGold() < $chosenItem->getPrice()) {
            printLine(translate("You don't have enough gold"));
            return;
        }
        $this->getPlayer()->setGold($this->getPlayer()->getGold() - $chosenItem->getPrice());
        $this->getPlayer()->getInventory()->addItem($chosenItem);

        $shop->removeItem($chosenItem);
        printLine(translate('You bought a ') . $chosenItem->getName());
    }

    /**
     * @throws Exception
     */
    function sell(): void
    {
        $shop = Shop::getInstance();

        if ($this->getPlayer()->getInventory()->isEmpty()) {
            printLine(translate('Your inventory is empty.'));
            return;
        }

        $items = $this->getPlayer()->getInventory()->getItems();
        $choices = [];
        foreach ($items as $item) {
            $choices[] = $item->getName() . ' (' . $item->getPrice()/2 . ' ' . translate('gold') . ')';
        }
        $choices[] = translate('Back');
        $choice = $this->askChoice($choices, 1, 100, translate('You have ') .
            $this->getPlayer()->getGold() .
            ' ' . translate('gold') . '. '. translate('Choose an item:'));
        if ($choice == count($choices)) {
            return;
        }
        $chosenItem = $items[$choice - 1];
        $this->getPlayer()->setGold($this->getPlayer()->getGold() + $chosenItem->getPrice()/2);
        $this->getPlayer()->getInventory()->removeItem($chosenItem);

        $shop->addItem($chosenItem);
        printLine(translate('You sold a ') . $chosenItem->getName());
    }
}