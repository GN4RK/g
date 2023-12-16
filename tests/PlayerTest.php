<?php
use PHPUnit\Framework\TestCase;
use YoannLeonard\G\Model\Entity\Player;
use YoannLeonard\G\Model\Move\Defend;

final class PlayerTest extends TestCase
{
    public function testPlayer()
    {
        $player = new Player("PlayerTest", 100, 100, 100);
        $this->assertEquals("PlayerTest", $player->getName());
        $this->assertEquals(100, $player->getmaxHealth());
        $this->assertEquals(100, $player->getBaseAttack());
        $this->assertEquals(100, $player->getBaseDefense());
        $this->assertEquals(100, $player->getHealth());
        $this->assertEquals(100, $player->getAttack());
        $this->assertEquals(100, $player->getDefense());
        $this->assertEquals(0, $player->getExperience());
        $this->assertEquals(10, $player->getGold());
        $this->assertEquals(1, $player->getLevel());

        $player->setName("PlayerTest2");
        $this->assertEquals("PlayerTest2", $player->getName());

        $player->setLevel(2);
        $this->assertEquals(2, $player->getLevel());

        $player->setExperience(100);
        $this->assertEquals(100, $player->getExperience());

        $player->setGold(100);
        $this->assertEquals(100, $player->getGold());

        $player->setHealth(50);
        $this->assertEquals(50, $player->getHealth());
        $this->assertEquals(100, $player->getmaxHealth());

        $player->setAttack(50);
        $this->assertEquals(50, $player->getAttack());
        $this->assertEquals(100, $player->getBaseAttack());

        $player->setDefense(50);
        $this->assertEquals(50, $player->getDefense());
        $this->assertEquals(100, $player->getBaseDefense());

        $player->setmaxHealth(200);
        $this->assertEquals(200, $player->getmaxHealth());
        $this->assertEquals(50, $player->getHealth());

        $player->setBaseAttack(200);
        $this->assertEquals(200, $player->getBaseAttack());
        $this->assertEquals(50, $player->getAttack());

        $player->setBaseDefense(200);
        $this->assertEquals(200, $player->getBaseDefense());
        $this->assertEquals(50, $player->getDefense());

        $this->assertEquals(true, $player->isAlive());
        $player->setHealth(0);
        $this->assertEquals(false, $player->isAlive());

        $player->setHealth(-1);
        $this->assertEquals(false, $player->isAlive());
    }

    public function testMoves()
    {
        $player = new Player("PlayerTest", 100, 100, 100);
        $player->setMove(new Defend($player));
        $this->assertEquals("defend", $player->getMove()->getName());
        $player->chooseActionFromString("attack");
        $this->assertEquals("attack", $player->getMove()->getName());
        $player->chooseActionFromString("flee");
        $this->assertEquals("flee", $player->getMove()->getName());
        $player->chooseActionFromString("defend");
        $this->assertEquals("defend", $player->getMove()->getName());

    }
}