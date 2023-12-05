<?php
require_once __DIR__ . '/vendor/autoload.php';

use YoannLeonard\G\Entities\Player;

function readInput(string $prompt = ''): string
{
    echo $prompt;
    return readline();
}

function readIntInput(string $prompt = ''): int
{
    $input = readInput($prompt);
    if (!is_numeric($input)) {
        printLine('Please enter a valid integer');
        return readIntInput($prompt);
    }
    return (int)$input;
}

function printLine(string $line = ''): void
{
    echo $line . PHP_EOL;
}

function printLines(array $lines = []): void
{
    foreach ($lines as $line) {
        printLine($line);
    }
}

function printLineWithBreak(string $line = ''): void
{
    printLine($line);
    printLine("--");
    printLine();
}

function printLinesWithBreak(array $lines = []): void
{
    printLines($lines);
    printLine("--");
    printLine();
}

function validateStats(Player $player): bool
{
    $health = $player->getHealth();
    $attack = $player->getAttack();
    $defense = $player->getDefense();

    if ($health < 50) {
        printLine('Health can\'t be less than 50');
        return false;
    }

    if ($attack < 1) {
        printLine('Attack can\'t be less than 1');
        return false;
    }

    if ($defense < 1) {
        printLine('Defense can\'t be less than 1');
        return false;
    }

    if ($health + $attack + $defense > 100) {
        printLine('You can\'t have more than 100 points in total');
        return false;
    }

    return true;

}

function createPlayer($playerName): Player
{
    if ($playerName == 'test') {
        return new Player($playerName, 50, 25, 25);
    }
    $health = readIntInput('Enter your health: ');
    $attack = readIntInput('Enter your attack: ');
    $defense = readIntInput('Enter your defense: ');

    $player = new Player($playerName, $health, $attack, $defense);

    if (!validateStats($player)) {
        return createPlayer($playerName);
    }

    return $player;
}

function main(): void
{
    $playerName = readInput('Enter your name: ');
    printLinesWithBreak([
        "Hello $playerName",
        "You have 100 stat points to distribute between health, attack and defense.",
        "Health can't be less than 50",
        "You can't have less than 1 point in any stat."
    ]);

    $player = createPlayer($playerName);

    if (!validateStats($player)) {
        main();
    }

    printLineWithBreak('Your stats are valid');
    printLineWithBreak($player);

    // main loop
    while (true) {
        $choice = readIntInput('What do you want to do? (1: Find Combat, 2: Check stats, 3: Quit): ');
        switch ($choice) {
            case 1:
                printLineWithBreak('Searching for combat...');


                break;
            case 2:
                printLinesWithBreak($player->displayStats());
                break;
            case 3:
                printLineWithBreak('Bye');
                exit;
            default:
                printLineWithBreak('Invalid choice');
                break;
        }
    }
}

main();