<?php

namespace YoannLeonard\G\Model;

use YoannLeonard\G\Model\Move\Attack;

use function YoannLeonard\G\printLinesWithBreak;

class Entity
{
    private string|false $entityName;
    private string|false $name;
    private string $state;
    private string $status;
    private int $maxHealth;
    private int $health;
    private int $baseAttack;
    private int $attack;
    private int $baseDefense;
    private int $defense;
    private int $experience;
    private int $gold;
    private Move $move;
    public Inventory $inventory;
    public Moveset $moveset;

    public function __construct(int $maxHealth, int $baseAttack, int $baseDefense)
    {
        $this->status = 'normal';
        $this->state = 'neutral';
        $this->maxHealth = $maxHealth;
        $this->health = $maxHealth;
        $this->baseAttack = $baseAttack;
        $this->attack = $baseAttack;
        $this->baseDefense = $baseDefense;
        $this->defense = $baseDefense;
        $this->experience = 0;
        $this->gold = 0;

        $entityClassPath = get_class($this);
        $nameParts = explode('\\', $entityClassPath);
        $this->entityName = end($nameParts);
        $this->name = $this->entityName;

        $this->move = new Attack($this);
        $this->moveset = new Moveset();
        $this->inventory = new Inventory();
    }

    public function getEntityName(): string
    {
        return $this->entityName;
    }

    public function isAlive(): bool
    {
        return $this->health > 0;
    }

    public function getHealth(): int
    {
        return $this->health;
    }

    public function getMaxHealth(): int
    {
        return $this->maxHealth;
    }

    public function getAttack(): int
    {
        return $this->attack;
    }

    public function getBaseAttack(): int
    {
        return $this->baseAttack;
    }

    public function getDefense(): int
    {
        return $this->defense;
    }

    public function getBaseDefense(): int
    {
        return $this->baseDefense;
    }

    public function setHealth(int $health): void
    {
        $this->health = $health;
    }

    public function setmaxHealth(int $maxHealth): void
    {
        $this->maxHealth = $maxHealth;
    }

    public function setAttack(int $attack): void
    {
        $this->attack = $attack;
    }

    public function setBaseAttack(int $baseAttack): void
    {
        $this->baseAttack = $baseAttack;
    }

    public function setDefense(int $defense): void
    {
        $this->defense = $defense;
    }

    public function setBaseDefense(int $baseDefense): void
    {
        $this->baseDefense = $baseDefense;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getStatus(): string
    {
        if ($this->status === null) {
            return 'normal';
        }
        return $this->status;
    }

    public function getState(): string
    {
        if ($this->state === null) {
            return 'ready';
        }
        return $this->state;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }

    public function setMove(Move $move): void
    {
        $this->move = $move;
    }

    public function getMove(): Move
    {
        return $this->move;
    }

    public function getExperience(): int
    {
        return $this->experience;
    }

    public function getGold(): int
    {
        return $this->gold;
    }

    public function setExperience(int $experience): void
    {
        $this->experience = $experience;
    }

    public function setGold(int $gold): void
    {
        $this->gold = $gold;
    }

    public function getInventory(): Inventory
    {
        return $this->inventory;
    }

    public function chooseRandomAction(): void
    {
        $randomNumber = mt_rand() / mt_getrandmax();

        $cumulativeProbability = 0;
        foreach ($this->getMoveset()->getMoves() as $move) {
            $cumulativeProbability += $move['probability'];
            if ($randomNumber <= $cumulativeProbability) {
                $this->setMove($move['move']);
                return;
            }
        }

        $this->setMove($this->getMoveset()->getMoves()[0]['move']);
    }

    public function chooseActionFromString(string $move): bool
    {
        // check if the move is in the moveset
        foreach ($this->getMoveset()->getMoves() as $moveInMoveset) {
            if ($moveInMoveset['move']->getName() === $move) {
                $this->setMove($moveInMoveset['move']);
                return true;
            }
        }
        return false;
    }

    public function updateState(): void
    {
        $this->setState($this->getMove()->getState());
    }

    public function getMoveset(): Moveset
    {
        return $this->moveset;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {

        return $this->name;
    }

    public function fullHeal(): void
    {
        $this->setHealth($this->getMaxHealth());
        $this->setAttack($this->getBaseAttack());
        $this->setDefense($this->getBaseDefense());
        $this->setStatus('normal');

    }

    public function displayStats(): void
    {
        printLinesWithBreak($this->getStats());
    }

    public function getStats(): array
    {
        return [
            "Name      : " . $this->getName(),
            "Health    : " . $this->getHealth() . '/' . $this->getMaxHealth(),
            "Attack    : " . $this->getAttack(),
            "Defense   : " . $this->getDefense(),
            "Status    : " . $this->getStatus(),
            "State     : " . $this->getState()
        ];
    }

    public function cancelBonus(): void
    {
        $this->setAttack($this->getBaseAttack());
        $this->setDefense($this->getBaseDefense());
    }

    public function isDefending(): bool
    {
        return $this->getState() === 'defending';
    }

    public function heal(int $heal): void
    {
        $this->setHealth($this->getHealth() + $heal);
        if ($this->getHealth() > $this->getMaxHealth()) {
            $this->setHealth($this->getMaxHealth());
        }
    }


}