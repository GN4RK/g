<?php

namespace YoannLeonard\G\Controller;

use YoannLeonard\G\Controller\Controller;
use YoannLeonard\G\model\Entity\Player;
use YoannLeonard\G\model\Entity;
use YoannLeonard\G\model\Entity\Fight;
use YoannLeonard\G\Game;
use YoannLeonard\G\model\Move\Attack;
use YoannLeonard\G\model\Move\Defense;
use YoannLeonard\G\model\Move\Flee;

use function YoannLeonard\G\printLine;

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
            $enemyChoice = $entity->chooseRandomAction();

            // display enemy action
            printLine($entity->getEntityName() . ' chose ' . $enemyChoice);

            // Player chooses an action
            $playerChoice = Game::getInstance()->askChoice([
                '1: Attack for ' . $player->getAttack() . ' damage',
                '2: Defend for ' . ($player->getDefense() * 2) . ' damage',
                '3: Run away'
            ]);

            if ($playerChoice == 1) {
                $player->setMove(new Attack($player));
            } elseif ($playerChoice == 2) {
                $player->setMove(new Defense($player));
            } elseif ($playerChoice == 3) {
                $player->setMove(new Flee($player));
            }

            // display player action
            printLine($player->getName() . ' chose ' . $player->getMove()->getName());

            // get bonus from moves
            $playerBonus = $player->getMove()->getBonus();

            
        }

        if ($player->isAlive()) {
            printLine($player->getName() . ' won the fight!');
        } else {
            printLine($entity->getEntityName() . ' won the fight!');
        }
    }

    public function attack(Entity $attacker, Entity $defender): void
    {
        $damage = $attacker->getAttack() - $defender->getDefense();
        if ($damage < 0) {
            $damage = 1;
        }
        $defender->setHealth($defender->getHealth() - $damage);
        printLine($attacker->getEntityName() . ' attacked ' . $defender->getEntityName() . ' for ' . $damage . ' damage.');
    }

    public function defense(Entity $attacker, Entity $defender): void
    {
        $damage = $attacker->getAttack() - ($defender->getDefense() * 2);
        if ($damage < 0) {
            $damage = 0;
        }
        $defender->setHealth($defender->getHealth() - $damage);
        if ($damage == 0) {
            printLine($defender->getEntityName() . ' attacked ' . $attacker->getEntityName() . ' but it had no effect.');
        } else {
            printLine($defender->getEntityName() . ' attacked ' . $attacker->getEntityName() . ' for ' . $damage . ' damage.');
        }
    }
}