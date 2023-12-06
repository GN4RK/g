<?php

namespace YoannLeonard\G;

use YoannLeonard\G\Controller\FightController;
use YoannLeonard\G\Entity\Player;
use YoannLeonard\G\Entity\Rat;

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

        var_dump($player);

        if (!$this->validateStats($player)) {
            return $this->createPlayer($playerName);
        }

        return $player;
    }

    public function validateStats(Player $player): bool
    {
        var_dump($player->getHealth());
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
        printLineWithBreak($this->getPlayer());

        // main loop
        while (true) {
            $choice = readIntInput('What do you want to do? (1: Find Combat, 2: Check stats, 3: Quit): ');
            switch ($choice) {
                case 1:
                    printLineWithBreak('Searching for combat...');

                    $fight = FightController::getInstance()->createFight($this->getPlayer(), new Rat());
                    printLineWithBreak('A fight has started between ' . $fight->getPlayer()->getName() . ' and ' . $fight->getEntity()->getEntityName() . '!');
                    FightController::getInstance()->startFight($fight);


                    break;
                case 2:
                    printLinesWithBreak($this->getPlayer()->displayStats());
                    break;
                case 3:
                    printLineWithBreak('Bye');
                    exit;
                default:
                    printLineWithBreak('Invalid choice');
                    break;
            }
        }
    }
}