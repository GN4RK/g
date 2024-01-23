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
use YoannLeonard\G\Model\Item\Cheese;
use YoannLeonard\G\Model\Item\RedPepper;
use YoannLeonard\G\Model\Item\SewerMap;
use YoannLeonard\G\Model\Shop;

// singleton class
class Game
{
    private static ?Game $instance = null;
    private ?Player $player = null;
    private string $lang = 'en_EN';

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
        $health = readIntInput($this->translate('enter your health'), 10, 18);
        $attack = readIntInput($this->translate('enter your attack'), 1, 9);
        $defense = readIntInput($this->translate('enter your defense'), 1, 9);

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
            printLine('Health can\'t be less than 10');
            return false;
        }

        if ($attack < 1) {
            printLine('Attack can\'t be less than 1');
            return false;
        }

        if ($defense < 1) {
            printLine('Defense can\'t be less than 1');
            return false;
        }

        if ($health + $attack + $defense > 20) {
            printLine('You can\'t have more than 20 points in total');
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
        printLineWithBreak($this->translate("welcome"));

        $choice = $this->askChoice([
            $this->translate('start a new game'),
            $this->translate('load a saved game')
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
                case 'Check stats':
                    printLines($this->getPlayer()->getStats());
                    break;
                case 'Check inventory':
                    $this->inventory();
                    break;
                case 'Save and quit':
                    $this->save();
                    exit;
                case 'Go to the shop':
                    $this->shop();
                    break;
                case 'Go to the sewer':
                    printLineWithBreak('Searching for combat...');
                    
                    $enemy = $this->randomEnemy();
                    printLineWithBreak('You found a ' . $enemy->getEntityName() . '!');
                    printLine($entityController->renderEntity($enemy));

                    $choiceEncounter = $this->askChoice([
                        'Fight',
                        'Run away'
                    ]);

                    if ($choiceEncounter == 2) {
                        printLine('You ran away!');
                        break;
                    }

                    $fight = $fightController->createFight($this->getPlayer(), $enemy);
                    printLineWithBreak('A fight has started between ' . $fight->getPlayer()->getName() . ' and ' . $fight->getEntity()->getEntityName() . '!');
                    $fightController->startFight($fight);
                    break;
                default:
                    printLine('Invalid choice');
                    break;
            }
        }

        printLineWithBreak('You died');
        printLineWithBreak('Game over');
    }

    function newGame(): void
    {
        $playerName = readInput($this->translate('enter your name'));
        printLinesWithBreak([
            "Hello $playerName",
            "You have 20 stat points to distribute between health, attack and defense.",
            "Health can't be less than 10",
            "You can't have less than 1 point in any stat."
        ]);

        $this->setPlayer($this->createPlayer($playerName));

        if (!$this->validateStats($this->getPlayer())) {
            main();
        }

        printLineWithBreak('Your stats are valid');
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

        $fileName = readInput('Enter a name for your save: ');
        // check if folder exists
        if (!file_exists('saves')) {
            mkdir('saves');
        }
        file_put_contents('saves/' . $fileName . '.sav', $serializedData);
        printLineWithBreak('Game saved');
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
            printLineWithBreak('No save found');
            return false;
        }

        $choices = [];
        foreach ($saves as $save) {
            if ($save == '.' || $save == '..') {
                continue;
            }
            $choices[] = $save;
        }

        $choice = $this->askChoice($choices, 1, count($choices), 'Choose a save:');
        $fileName = $choices[$choice - 1];

        $serializedData = file_get_contents('saves/' . $fileName);
        $data = unserialize($serializedData);
        $this->setPlayer($data['player']);
        Shop::setInstance($data['shop']);
        printLineWithBreak('Player loaded');
        printLines($this->getPlayer()->getStats());
        return true;
    }

    /**
     * @throws Exception
     */
    function askChoice(array $choices, int $min = 1, int $max = 100, string $prompt = 'What do you want to do?'): int
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

        printLineWithBreak();
        printLines(array_merge(["[green]" . $prompt . "[reset]"], $choices));
        $choice = readIntInput('> Your choice: ', $min, $max);
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
            printLine('Your inventory is empty.');
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
            array_merge($displayedItems, ['Back']),
            1,
            count($firstAndQuantity) + 1,
            'Choose an item:'
        );

        if ($choice == count($firstAndQuantity) + 1) {
            return;
        }

        $chosenItem = $firstAndQuantity[$choice - 1][0];

        $choice = $this->askChoice([
            'Use',
            'View',
            'Drop',
            'Back'
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
                printLine('Invalid choice');
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

        while ($menuAction[$choice-1] != 'Back'){
            $choice = $this->askChoice($menuAction);
            switch ($menuAction[$choice-1]) {
                case 'Buy':
                    $this->buy();
                    break;
                case 'Sell':
                    $this->sell();
                    break;
                case 'Talk':
                    printLine('The shopkeeper says: "Hello adventurer!"');
                    break;
                case 'Leave':
                    return;
                default:
                    printLine('Invalid choice');
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
            printLine('The shop is empty.');
            return;
        }

        $items = $shop->getItems();
        $choices = [];
        foreach ($items as $item) {
            $choices[] = $item->getName() . ' (' . $item->getPrice() . ' gold)';
        }
        $choices[] = 'Back';
        $choice = $this->askChoice($choices, 1, 100, 'You have ' . $this->getPlayer()->getGold() . ' gold. Choose an item:');
        if ($choice == count($choices)) {
            return;
        }
        $chosenItem = $items[$choice - 1];
        if ($this->getPlayer()->getGold() < $chosenItem->getPrice()) {
            printLine('You don\'t have enough gold');
            return;
        }
        $this->getPlayer()->setGold($this->getPlayer()->getGold() - $chosenItem->getPrice());
        $this->getPlayer()->getInventory()->addItem($chosenItem);

        $shop->removeItem($chosenItem);
        printLine('You bought a ' . $chosenItem->getName());
    }

    /**
     * @throws Exception
     */
    function sell(): void
    {
        $shop = Shop::getInstance();

        if ($this->getPlayer()->getInventory()->isEmpty()) {
            printLine('Your inventory is empty.');
            return;
        }

        $items = $this->getPlayer()->getInventory()->getItems();
        $choices = [];
        foreach ($items as $item) {
            $choices[] = $item->getName() . ' (' . $item->getPrice()/2 . ' gold)';
        }
        $choices[] = 'Back';
        $choice = $this->askChoice($choices, 1, 100, 'You have ' . $this->getPlayer()->getGold() . ' gold. Choose an item:');
        if ($choice == count($choices)) {
            return;
        }
        $chosenItem = $items[$choice - 1];
        $this->getPlayer()->setGold($this->getPlayer()->getGold() + $chosenItem->getPrice()/2);
        $this->getPlayer()->getInventory()->removeItem($chosenItem);

        $shop->addItem($chosenItem);
        printLine('You sold a ' . $chosenItem->getName());
    }

    function translate(string $key): string
    {
        $translations = include(__DIR__ . '/translations/' . $this->lang . '.php');
        return $translations[$key];
    }

        
}