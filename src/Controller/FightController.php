<?php

namespace YoannLeonard\G\Controller;

use Exception;
use YoannLeonard\G\Model\Entity\Player;
use YoannLeonard\G\Model\Entity;
use YoannLeonard\G\Model\Entity\Fight;
use YoannLeonard\G\Game;

use function YoannLeonard\G\clearScreen;
use function YoannLeonard\G\pressEnterToContinue;
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

    /**
     * @throws Exception
     */
    public function startFight(Fight $fight): void
    {
        $player = $fight->getPlayer();
        $entity = $fight->getEntity();
        $entityController = EntityController::getInstance();

        while ($player->isAlive() && $entity->isAlive()) {
            clearScreen();
            // displaying entity
            printLine($entityController->renderEntity($entity));

            printLine('Turn ' . $fight->getTurn() . ':');
            printLine($player->getName() . ' has ' . $player->getHealth() . ' health left.');
            printLine($entity->getEntityName() . ' has ' . $entity->getHealth() . ' health left.');

            // Enemy chooses a random action
            $entity->chooseRandomAction();
            $enemyChoice = $entity->getMove()->getName();

            // display enemy action
            printLine($entity->getEntityName() . ' chose to ' . $enemyChoice);

            $playerChoice = 'Check stats';

            $playerActions = $player->getFightActions();

            while (str_contains($playerChoice, 'Check')) {

                $playerChoiceInMenu = Game::getInstance()->askChoice($playerActions);
                $playerChoice = $playerActions[$playerChoiceInMenu - 1];

                switch($playerChoice) {
                    case 'attack':
                        $player->chooseActionFromString('attack');
                        break;
                    case 'defend':
                        $player->chooseActionFromString('defend');
                        break;
                    case 'flee':
                        $player->chooseActionFromString('flee');
                        break;
                    case 'Check stats':
                        $player->displayStats();
                        break;
                    case 'Check inventory':
                        if ($player->getInventory()->isEmpty()) {
                            printLine('Your inventory is empty.');
                            break;
                        }
                        $this->getGame()->inventory();
                        break;
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

            pressEnterToContinue();
            
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
        $inventoryController = InventoryController::getInstance();

        $player = $fight->getPlayer();
        $entity = $fight->getEntity();

        printLine($player->getName() . ' gained ' . $entity->getExperience() . ' experience and ' . $entity->getGold() . ' gold!');
        $player->addGold($entity->getGold());

        if ($player->addExperience($entity->getExperience())) {
            printLine($player->getName() . ' gained a level!');
            $player->displayStats();
        }

        $lootedItems = $inventoryController->lootItem($entity->getInventory());
        $inventoryController->addItems($lootedItems, $player->getInventory());

        pressEnterToContinue();
        
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