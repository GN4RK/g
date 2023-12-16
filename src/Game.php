<?php

namespace YoannLeonard\G;

use Exception;
use YoannLeonard\G\Controller\EntityController;
use YoannLeonard\G\Controller\FightController;
use YoannLeonard\G\Model\Entity;
use YoannLeonard\G\Model\Entity\Player;
use YoannLeonard\G\Model\Entity\Pusheen;
use YoannLeonard\G\Model\Entity\Rat;

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
        $health = readIntInput('Enter your health: ', 10, 18);
        $attack = readIntInput('Enter your attack: ', 1, 9);
        $defense = readIntInput('Enter your defense: ', 1, 9);

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

    public function start(): void
    {
        printLineWithBreak('Welcome to the game');

        $choice = $this->askChoice([
            'Start a new game',
            'Load a game'
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

    function mainLoop()
    {
        $fightController = FightController::getInstance();
        $entityController = EntityController::getInstance();
        
        while ($this->getPlayer()->isAlive()) {

            $choice = $this->askChoice([
                'Shopping',
                'Find Combat',
                'Check stats',
                'Check inventory',
                'Save and quit'
            ]);

            switch ($choice) {
                case 1:
                    printLineWithBreak('Shopping not implemented yet');
                    break;
                case 2:
                    printLineWithBreak('Searching for combat...');
                    
                    $enemy = $this->randomEnemy();
                    printLineWithBreak('You found a ' . $enemy->getEntityName() . '!');
                    printLine($entityController->renderEntity($enemy));

                    $choiceEncounter = $this->askChoice([
                        'Fight',
                        'Run away'
                    ]);

                    if ($choiceEncounter == 2) {
                        printLineWithBreak('You ran away!');
                        break;
                    }

                    $fight = $fightController->createFight($this->getPlayer(), $enemy);
                    printLineWithBreak('A fight has started between ' . $fight->getPlayer()->getName() . ' and ' . $fight->getEntity()->getEntityName() . '!');
                    $fightController->startFight($fight);


                    break;
                case 3:
                    printLinesWithBreak($this->getPlayer()->getStats());
                    break;
                case 4:
                    if ($this->getPlayer()->getInventory()->isEmpty()) {
                        printLine('Your inventory is empty.');
                        break;
                    }
                    printLines($this->getPlayer()->getInventory()->getItems());
                    break;
                case 5:
                    $this->save();                    
                    exit;
                default:
                    printLineWithBreak('Invalid choice');
                    break;
            }
        }

        printLineWithBreak('You died');
        printLineWithBreak('Game over');
    }

    function newGame()
    {
        $playerName = readInput('Enter your name: ');
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
        printLinesWithBreak($this->getPlayer()->getStats());
    }

    function save()
    {
        $player = $this->getPlayer();
        $playerData = serialize($player);
        $fileName = readInput('Enter a name for your save: ');
        // check if folder exists
        if (!file_exists('saves')) {
            mkdir('saves');
        }
        file_put_contents('saves/' . $fileName . '.sav', $playerData);
        printLineWithBreak('Player saved');
    }

    function loadGame()
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

        $playerData = file_get_contents('saves/' . $fileName);
        $player = unserialize($playerData);
        $this->setPlayer($player);
        printLineWithBreak('Player loaded');
        printLinesWithBreak($this->getPlayer()->getStats());
        return true;
    }

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

        printLines(array_merge([$prompt], $choices));

        $choice = readIntInput('> Your choice: ', $min, $max);
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

        
}