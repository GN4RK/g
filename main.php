<?php
namespace YoannLeonard\G;

require_once __DIR__ . '/vendor/autoload.php';

use YoannLeonard\G\Game;

function readInput(string $prompt = ''): string
{
    echo $prompt;
    return readline();
}

function readIntInput(string $prompt = '', int $min = 0, int $max = 100): int
{
    $input = readInput($prompt, $min, $max);
    if (!is_numeric($input)) {
        printLine('Please enter a valid integer');
        return readIntInput($prompt, $min, $max);
    }
    if ($input < $min || $input > $max) {
        printLine('Please enter a valid integer between ' . $min . ' and ' . $max);
        return readIntInput($prompt, $min, $max);
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

function main(): void
{
    $game = Game::getInstance();
    $game->start();
}

main();