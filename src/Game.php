<?php

namespace YoannLeonard\G;

use Exception;
use YoannLeonard\G\Controller\FightController;
use YoannLeonard\G\model\Entity;
use YoannLeonard\G\model\Entity\Player;
use YoannLeonard\G\model\Entity\Rat;

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
            return new Player($playerName, 50, 25, 25);
        }
        $health = readIntInput('Enter your health: ', 50, 98);
        $attack = readIntInput('Enter your attack: ', 1, 49);
        $defense = readIntInput('Enter your defense: ', 1, 49);

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

        if ($health < 50) {
            printLine('Health can\'t be less than 50');
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

        if ($health + $attack + $defense > 100) {
            printLine('You can\'t have more than 100 points in total');
            return false;
        }

        return true;

    }

    public function start(): void
    {
        printLineWithBreak('Welcome to the game');

        $choice = $this->askChoice([
            '1: Start a new game',
            '2: Load a game'
        ]);

        if ($choice == 1) {
            $this->newGame();
        } else {
            $this->loadGame();
        }

        $this->mainLoop();
    }

    function mainLoop()
    {
        $fightController = FightController::getInstance();
        
        while ($this->getPlayer()->isAlive()) {

            $choice = $this->askChoice([
                '1: Shopping',
                '2: Find Combat',
                '3: Check stats',
                '4: Save and quit'
            ]);

            switch ($choice) {
                case 1:
                    printLineWithBreak('Shopping not implemented yet');
                    break;
                case 2:
                    printLineWithBreak('Searching for combat...');
                    
                    $enemy = $this->randomEnemy();
                    printLineWithBreak('You found a ' . $enemy->getEntityName() . '!');

                    $choiceEncounter = $this->askChoice([
                        '1: Fight',
                        '2: Run away'
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
            "You have 100 stat points to distribute between health, attack and defense.",
            "Health can't be less than 50",
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
        file_put_contents('player.txt', $playerData);
        printLineWithBreak('Player saved');
    }

    function loadGame()
    {
        $playerData = file_get_contents('player.txt');
        $player = unserialize($playerData);
        $this->setPlayer($player);
        printLineWithBreak('Player loaded');
        printLinesWithBreak($this->getPlayer()->getStats());
    }

    function askChoice(array $choices, int $min = 1, int $max = 100): int
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

        printLines(array_merge(['What do you want to do?'], $choices));

        $choice = readIntInput('> Your choice: ', $min, $max);
        return $choice;
    }

    function randomEnemy(): Entity
    {
        $enemies = [
            new Rat(),
        ];

        $randomIndex = rand(0, count($enemies) - 1);
        return $enemies[$randomIndex];

    }

        
}