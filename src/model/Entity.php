<?php

namespace YoannLeonard\G\model;

use YoannLeonard\G\model\Move\Attack;

use function YoannLeonard\G\printLine;
use function YoannLeonard\G\printLinesWithBreak;

class Entity
{
    private $entityName;
    private $name;
    private $state;
    private $status;
    private $maxHealth;
    private $health;
    private $baseAttack;
    private $attack;
    private $baseDefense;
    private $defense;
    private Move $move;

    public $moveset;

    public const STATE = [
        'neutral' => 1,
        'attacking' => 2,
        'defending' => 3,
        'fleeing' => 4
    ];

    public const STATUS = [
        'normal' => 1,
        'poisoned' => 2,
        'burned' => 3,
        'paralyzed' => 4,
        'asleep' => 5,
        'frozen' => 6,
        'confused' => 7,
        'flee' => 8,
        'dead' => 9
    ];

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

        $entityClassPath = get_class($this);
        $nameParts = explode('\\', $entityClassPath);
        $this->entityName = end($nameParts);
        $this->name = $this->entityName;

        $this->moveset = new Moveset();
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

    public function getmaxHealth(): int
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
        if ($this->move === null) {
            return new Attack($this);
        }
        return $this->move;
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

    public function displayStats(): void
    {
        printLinesWithBreak($this->getStats());
    }

    public function getStats(): array
    {
        return [
            "Name      : " . $this->getName(),
            "Health    : " . $this->getHealth() . '/' . $this->getmaxHealth(),
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


}