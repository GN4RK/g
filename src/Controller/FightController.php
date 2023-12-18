<?php

namespace YoannLeonard\G\Controller;

use YoannLeonard\G\Controller\Controller;
use YoannLeonard\G\Model\Entity\Player;
use YoannLeonard\G\Model\Entity;
use YoannLeonard\G\Model\Entity\Fight;
use YoannLeonard\G\Game;

use function YoannLeonard\G\printLine;
use function YoannLeonard\G\printLines;
use function YoannLeonard\G\printLineWithBreak;

class FightController extends Controller
{
    private static ?FightController $instance = null;

    // list of all fights
    private array $fights = [];

    public static function getInstance(): FightController
    {
        if (self::$instance === null) {
            self::$instance = new FightController(Game::getInstance());
        }
        return self::$instance;
    }

    public function createFight(Player $player, Entity $entity): Fight
    {
        $fight = new Fight($player, $entity);
        $this->fights[] = $fight;
        return $fight;
    }

    public function startFight(Fight $fight): void
    {
        $player = $fight->getPlayer();
        $entity = $fight->getEntity();

        while ($player->isAlive() && $entity->isAlive()) {
            printLine('Turn ' . $fight->getTurn() . ':');
            printLine($player->getName() . ' has ' . $player->getHealth() . ' health left.');
            printLine($entity->getEntityName() . ' has ' . $entity->getHealth() . ' health left.');

            // Enemy chooses a random action
            $entity->chooseRandomAction();
            $enemyChoice = $entity->getMove()->getName();

            // display enemy action
            printLine($entity->getEntityName() . ' chose to ' . $enemyChoice);

            $playerChoice = 4;

            while ($playerChoice >= 4) {
                // Player chooses an action
                $playerChoice = Game::getInstance()->askChoice([
                    'Attack for ' . $player->getAttack() . ' damage',
                    'Defend for ' . ($player->getDefense() * 2) . ' damage',
                    'Run away',
                    'View stats',
                    'Check inventory'
                ]);

                if ($playerChoice == 1) {
                    $player->chooseActionFromString('attack');
                } elseif ($playerChoice == 2) {
                    $player->chooseActionFromString('defend');
                } elseif ($playerChoice == 3) {
                    $player->chooseActionFromString('flee');
                } elseif ($playerChoice == 4) {
                    $player->displayStats();
                } elseif ($playerChoice == 5) {
                    if ($player->getInventory()->isEmpty()) {
                        printLine('Your inventory is empty.');
                        continue;
                    }
                    printLines($player->getInventory()->getItems());
                }
            }

            // display player action
            printLine($player->getName() . ' chose to ' . $player->getMove()->getName());

            // get bonus from moves
            $entity->getMove()->getBonus();
            $player->getMove()->getBonus();

            // update state
            $entity->updateState();
            $player->updateState();

            // apply moves
            $this->applyMoves($fight);
            
            // cancel bonus from moves
            $entity->cancelBonus();
            $player->cancelBonus();

            // end of turn
            $fight->incrementTurn();
            printLineWithBreak();

            
        }

        if ($player->isAlive()) {
            printLine($player->getName() . ' won the fight!');
            $this->endFight($fight);
        } else {
            printLine($entity->getEntityName() . ' won the fight!');
        }
    }

    public function endFight(Fight $fight): void
    {
        $player = $fight->getPlayer();
        $entity = $fight->getEntity();

        printLine($player->getName() . ' gained ' . $entity->getExperience() . ' experience and ' . $entity->getGold() . ' gold!');

        if ($player->addExperience($entity->getExperience())) {
            printLine($player->getName() . ' gained a level!');
            $player->displayStats();
        }
        $player->addGold($entity->getGold());

        // get loot
        $loots = $entity->getInventory()->getItems();

        foreach ($loots as $loot) {
            printLine('You found a ' . $loot->getName() . '!');
            $player->getInventory()->addItem($loot);
        }
        
    }

    public function applyMoves(Fight $fight): void
    {
        $player = $fight->getPlayer();
        $entity = $fight->getEntity();

        $first = $player;
        $second = $entity;
        if ($entity->getMove()->getName() == 'defend') {
            $first = $entity;
            $second = $player;
        }

        $first->getMove()->apply($second);
        // check if the entity is still alive
        if (!$second->isAlive()) {
            return;
        }
        $second->getMove()->apply($first);
    }

    public function getFights(): array
    {
        return $this->fights;
    }
}