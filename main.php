<?php
namespace YoannLeonard\G;

require_once __DIR__ . '/vendor/autoload.php';

use YoannLeonard\G\Game;

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

function main(): void
{
    $game = Game::getInstance();
    $game->start();
}

main();