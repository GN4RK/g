<?php

namespace YoannLeonard\G\Model\Entity;

use YoannLeonard\G\Model\Entity;
use YoannLeonard\G\Model\Move\Attack;
use YoannLeonard\G\Model\Move\Defend;
use YoannLeonard\G\Model\Move\Flee;

class Player extends Entity
{
    private int $level;
    private bool $hasAccessToShop;
    private bool $hasAccessToSewer;
    private bool $hasAccessToRatDanceParty;


    public function __construct(string $name, int $health, int $attack, int $defense)
    {
        parent::__construct($health, $attack, $defense);
        $this->setName($name);
        $this->level = 1;
        parent::setExperience(0);
        parent::setGold(10);

        $this->hasAccessToShop = true;
        $this->hasAccessToSewer = false;
        $this->hasAccessToRatDanceParty = false;

        $this->moveset->addMove(new Attack($this));
        $this->moveset->addMove(new Defend($this));
        $this->moveset->addMove(new Flee($this));

    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    public function levelUp(): void
    {
        $this->level++;
        parent::setmaxHealth(parent::getMaxHealth() + 2);
        parent::setBaseAttack(parent::getBaseAttack() + 1);
        parent::setBaseDefense(parent::getBaseDefense() + 1);
        parent::fullHeal();
    }

    // return true if player level up
    public function addExperience(int $experience): bool
    {
        parent::setExperience(parent::getExperience() + $experience);
        if (parent::getExperience() >= 20) {
            $this->levelUp();
            parent::setExperience(parent::getExperience() - 20);
            return true;
        }
        return false;
    }

    public function addGold(int $gold): void
    {
        parent::setGold(parent::getGold() + $gold);
    }

    public function __toString(): string
    {
        return "Player: ". $this->getName() .", Level: $this->level, Health: ".parent::getHealth().", 
        Attack: ". parent::getAttack() .", Defense: ". parent::getDefense() .", Experience: ". parent::getExperience() .", Gold: ". parent::getGold();
    }

    public function getStats(): array
    {
        return [
            "Player    : ". $this->getName(),
            "Level     : $this->level",
            $this->getHealthStatus(),
            $this->getAttackStatus(),
            $this->getDefenseStatus(),
            "Status    : ".parent::getStatus(),
            "Experience: ".parent::getExperience(),
            "Gold      : ".parent::getGold()
        ];
    }
    
    private function getHealthStatus(): string
    {
        $health = parent::getHealth();
        $maxHealth = parent::getMaxHealth();
    
        if ($health >= $maxHealth / 2) {
            return "Health    : [green]".$health . "/" . $maxHealth . "[reset]";
        }
        if ($health < $maxHealth && $health > $maxHealth / 4) {
            return "Health    : [yellow]".$health . "/" . $maxHealth . "[reset]";
        }
        return "Health    : [red]".$health . "/" . $maxHealth . "[reset]";
    }
    
    private function getAttackStatus(): string
    {
        $attack = parent::getAttack();
        $baseAttack = parent::getBaseAttack();
        $attackBonus = $attack - $baseAttack;
    
        if ($attackBonus == 0) {
            return "Attack    : ".$baseAttack . "[grey]+". $attackBonus . "[reset]";
        }
        if ($attackBonus > 0) {
            return "Attack    : ".$baseAttack . "[green]+". $attackBonus . "[reset]";
        }
        return "Attack    : ".$baseAttack . "[red]-". $attackBonus . "[reset]";
    }
    
    private function getDefenseStatus(): string
    {
        $defense = parent::getDefense();
        $baseDefense = parent::getBaseDefense();
        $defenseBonus = $defense - $baseDefense;
    
        if ($defenseBonus == 0) {
            return "Defense   : ".$baseDefense . "[grey]+". $defenseBonus . "[reset]";
        }
        if ($defenseBonus > 0) {
            return "Defense   : ".$baseDefense . "[green]+". $defenseBonus . "[reset]";
        }
        return "Defense   : ".$baseDefense . "[red]-". $defenseBonus . "[reset]";
    }

    public function getMenuActions(): array
    {
        $actions = [
            'Check stats',
            'Check inventory',
            'Save and quit',
        ];

        if ($this->hasAccessToShop()) {
            $actions[] = 'Go to the shop';
        }

        if ($this->hasAccessToSewer()) {
            $actions[] = 'Go to the sewer';
        }
        return $actions;
    }

    public function getFightActions(): array
    {
        $actions = [];
        foreach ($this->moveset->getMoves() as $move) {
            $actions[] = $move["move"]->getName();
        }

        $actions[] = 'Check stats';
        $actions[] = 'Check inventory';

        return $actions;
    }

    public function hasAccessToShop(): bool
    {
        return $this->hasAccessToShop;
    }

    public function setHasAccessToShop(bool $hasAccessToShop): void
    {
        $this->hasAccessToShop = $hasAccessToShop;
    }

    public function hasAccessToSewer(): bool
    {
        return $this->hasAccessToSewer;
    }

    public function setHasAccessToSewer(bool $hasAccessToSewer): void
    {
        $this->hasAccessToSewer = $hasAccessToSewer;
    }

    public function hasAccessToRatDanceParty(): bool
    {
        return $this->hasAccessToRatDanceParty;
    }

    public function setHasAccessToRatDanceParty(bool $hasAccessToRatDanceParty): void
    {
        $this->hasAccessToRatDanceParty = $hasAccessToRatDanceParty;
    }

    
}