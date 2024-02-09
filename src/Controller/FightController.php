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
use function YoannLeonard\G\printLineWithBreak;
use function YoannLeonard\G\translate;

class FightController extends Controller
{
    private static ?FightController $instance = null;

    // list of all fights
    private array $fights = [];

    public static function getInstance(): FightController
    {
        if (self::$instance === null) {
            self::$instance = new FightController();
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

        while ($player->isAlive() && $entity->isAlive()) {
            clearScreen();
            $this->displayFightStatus($fight);
            $this->handleEntityTurn($fight);
            $this->handlePlayerTurn($fight);

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
            printLine($player->getName() . ' ' . translate('won the fight!'));
            $this->endFight($fight);
        } else {
            printLine($entity->getEntityName() . ' ' . translate('won the fight!'));
        }
    }

    public function displayFightStatus(Fight $fight): void
    {
        $player = $fight->getPlayer();
        $entity = $fight->getEntity();
        $entityController = EntityController::getInstance();

        // displaying entity
        printLine($entityController->renderEntity($entity));

        printLine(translate('Turn') . ' ' . $fight->getTurn() . ':');
        printLine(
            $player->getName() . ' ' . translate('has') . ' ' . $player->getHealth() . ' ' .
            translate('health left.'));
        printLine(
            $entity->getEntityName() . ' ' . translate('has') . ' ' . $entity->getHealth() . ' ' .
            translate('health left.'));
    }

    public function handleEntityTurn(Fight $fight): void
    {
        $entity = $fight->getEntity();

        // Enemy chooses a random action
        $entity->chooseRandomAction();
        $enemyChoice = $entity->getMove()->getName();

        // display enemy action
        printLine($entity->getEntityName() . ' ' . translate('chose to') . ' ' . $enemyChoice);
    }

    /**
     * @throws Exception
     */
    public function handlePlayerTurn(Fight $fight): void
    {
        $player = $fight->getPlayer();
        $playerChoice = translate('Check stats');
        $playerActions = $player->getFightActions();

        while (str_contains($playerChoice, translate('Check'))) {

            $playerChoiceInMenu = Game::getInstance()->askChoice($playerActions);
            $playerChoice = $playerActions[$playerChoiceInMenu - 1];

            switch($playerChoice) {
                case translate('attack'):
                    $player->chooseActionFromString('attack');
                    break;
                case translate('defend'):
                    $player->chooseActionFromString('defend');
                    break;
                case translate('flee'):
                    $player->chooseActionFromString('flee');
                    break;
                case translate('Check stats'):
                    $player->displayStats();
                    break;
                case translate('Check inventory'):
                    if ($player->getInventory()->isEmpty()) {
                        printLine(translate('Your inventory is empty.'));
                        break;
                    }
                    $this->getGame()->inventory();
                    break;

                default:
                    printLine(translate('Invalid choice'));
                    exit('KO');
            }
        }

        // display player action
        printLine($player->getName() . ' ' . translate('chose to') . ' ' . $player->getMove()->getName());
    }

    public function endFight(Fight $fight): void
    {
        $inventoryController = InventoryController::getInstance();

        $player = $fight->getPlayer();
        $entity = $fight->getEntity();

        printLine(
            $player->getName() . ' ' . translate('gained') . ' ' . $entity->getExperience() .
            ' ' . translate('experience') . ' ' . translate('and') . ' ' .
            $entity->getGold() . ' ' . translate('gold') . '!'
        );
        $player->addGold($entity->getGold());

        if ($player->addExperience($entity->getExperience())) {
            printLine($player->getName() . ' ' . translate('gained a level!'));
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