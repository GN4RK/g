<?php

namespace YoannLeonard\G;

use YoannLeonard\G\Controller\FightController;
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
        $health = readIntInput('Enter your health: ');
        $attack = readIntInput('Enter your attack: ');
        $defense = readIntInput('Enter your defense: ');

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
        printLines([
            '1: Start a new game',
            '2: Load a game'
        ]);
        $choice = readIntInput('Enter your choice: ');
        printLineWithBreak('');

        while ($choice < 1 || $choice > 2) {
            printLineWithBreak('/!\ Invalid choice');
            printLines([
                '1: Start a new game',
                '2: Load a game'
            ]);
            $choice = readIntInput('Enter your choice: ');
            printLineWithBreak('');
        }

        if ($choice == 1) {
            $this->newGame();
        } else {
            $this->loadGame();
        }

        // main loop
        while (true) {
            printLines([
                'What do you want to do?',
                '1: Shopping',
                '2: Find Combat',
                '3: Check stats',
                '4: Save and quit'
            ]);
            $choice = readIntInput('Enter your choice: ');
            switch ($choice) {
                case 1:
                    printLineWithBreak('Shopping not implemented yet');
                    break;
                case 2:
                    printLineWithBreak('Searching for combat...');
                    
                    // $enemy = randomEnemy();
                    $enemy = new Rat();


                    $fight = FightController::getInstance()->createFight($this->getPlayer(), $enemy);
                    printLineWithBreak('A fight has started between ' . $fight->getPlayer()->getName() . ' and ' . $fight->getEntity()->getEntityName() . '!');
                    FightController::getInstance()->startFight($fight);


                    break;
                case 3:
                    printLinesWithBreak($this->getPlayer()->displayStats());
                    break;
                case 4:
                    $this->save();                    
                    exit;
                default:
                    printLineWithBreak('Invalid choice');
                    break;
            }
        }
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
        printLinesWithBreak($this->getPlayer()->displayStats());
    }

    function save()
    {
        // save the Player object to a file
        $player = $this->getPlayer();
        
        $playerData = serialize($player);
        file_put_contents('player.txt', $playerData);


    }

    function loadGame()
    {
        // load the Player object from a file
        $playerData = file_get_contents('player.txt');
        $player = unserialize($playerData);
        $this->setPlayer($player);
        printLineWithBreak('Player loaded');
        printLinesWithBreak($this->getPlayer()->displayStats());
    }
        
}