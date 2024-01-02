<?php
use PHPUnit\Framework\TestCase;
use YoannLeonard\G\Model\Entity\Rat;

final class RatTest extends TestCase
{
    public function testRat()
    {
        $rat = new Rat();
        $this->assertEquals("Rat", $rat->getEntityName());
        $this->assertEquals(10, $rat->getMaxHealth());
        $this->assertEquals(5, $rat->getBaseAttack());
        $this->assertEquals(5, $rat->getBaseDefense());
        $this->assertEquals(10, $rat->getHealth());
        $this->assertEquals(5, $rat->getAttack());
        $this->assertEquals(5, $rat->getDefense());
        $this->assertEquals(5, $rat->getExperience());
        $this->assertEquals(0, $rat->getGold());

        $rat->setExperience(100);
        $this->assertEquals(100, $rat->getExperience());

        $rat->setGold(100);
        $this->assertEquals(100, $rat->getGold());

        $rat->setHealth(50);
        $this->assertEquals(50, $rat->getHealth());
        $this->assertEquals(10, $rat->getMaxHealth());

        $rat->setAttack(50);
        $this->assertEquals(50, $rat->getAttack());
        $this->assertEquals(5, $rat->getBaseAttack());

        $rat->setDefense(50);
        $this->assertEquals(50, $rat->getDefense());
        $this->assertEquals(5, $rat->getBaseDefense());

        $rat->setmaxHealth(200);
        $this->assertEquals(200, $rat->getMaxHealth());
        $this->assertEquals(50, $rat->getHealth());

        $rat->setBaseAttack(200);
        $this->assertEquals(200, $rat->getBaseAttack());
        $this->assertEquals(50, $rat->getAttack());

        $rat->setBaseDefense(200);
        $this->assertEquals(200, $rat->getBaseDefense());
        $this->assertEquals(50, $rat->getDefense());

        $this->assertEquals(true, $rat->isAlive());
        $rat->setHealth(0);
        $this->assertEquals(false, $rat->isAlive());
    }
}