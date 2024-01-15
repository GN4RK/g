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
        $stats = [
            "Player    : ". $this->getName(),
            "Level     : $this->level"
        ];

        if (parent::getHealth() == parent::getMaxHealth()) {
            $stats[] = "Health    : [green]".parent::getHealth() . "/" . parent::getMaxHealth() . "[reset]";
        }
        if (parent::getHealth() < parent::getMaxHealth() && parent::getHealth() > parent::getMaxHealth() / 2) {
            $stats[] = "Health    : [yellow]".parent::getHealth() . "/" . parent::getMaxHealth() . "[reset]";
        }
        if (parent::getHealth() <= parent::getMaxHealth() / 2) {
            $stats[] = "Health    : [red]".parent::getHealth() . "/" . parent::getMaxHealth() . "[reset]";
        }

        $attackBonus = parent::getAttack() - parent::getBaseAttack();
        if ($attackBonus == 0) {
            $stats[] = "Attack    : ".parent::getAttack() . "[grey]+". $attackBonus . "[reset]";
        }
        if ($attackBonus > 0) {
            $stats[] = "Attack    : ".parent::getAttack() . "[green]+". $attackBonus . "[reset]";
        }
        if ($attackBonus < 0) {
            $stats[] = "Attack    : ".parent::getAttack() . "[red]-". $attackBonus . "[reset]";
        }

        $defenseBonus = parent::getDefense() - parent::getBaseDefense();
        if ($defenseBonus == 0) {
            $stats[] = "Defense   : ".parent::getDefense() . "[grey]+". $defenseBonus . "[reset]";
        }
        if ($defenseBonus > 0) {
            $stats[] = "Defense   : ".parent::getDefense() . "[green]+". $defenseBonus . "[reset]";
        }
        if ($defenseBonus < 0) {
            $stats[] = "Defense   : ".parent::getDefense() . "[red]-". $defenseBonus . "[reset]";
        }

        $stats[] = "Status    : ".parent::getStatus();
        $stats[] = "Experience: ".parent::getExperience();
        $stats[] = "Gold      : ".parent::getGold();

        return $stats;
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