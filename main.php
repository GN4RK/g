<?php
namespace YoannLeonard\G;

require_once __DIR__ . '/vendor/autoload.php';

use YoannLeonard\G\Game;

define('LANG', 'en_EN'); // default language (english)

function readInput(string $prompt = ''): string
{
    printText($prompt);
    return readline();
}

function readIntInput(string $prompt = '', int $min = 0, int $max = 100): int
{
    $input = readInput($prompt);
    if (!is_numeric($input)) {
        printLine(translate('Please enter a valid integer'));
        return readIntInput($prompt, $min, $max);
    }
    if ($input < $min || $input > $max) {
        printLine(translate('Please enter a valid integer between ') . $min . " " . translate('and') . " " . $max);
        return readIntInput($prompt, $min, $max);
    }
    return (int)$input;
}

function printText(string $string = ''): void
{
    foreach (str_split($string) as $char) {
        echo $char;
    }
}

function printLine(string $line = ''): void
{
    // replace color markers with ANSI escape sequences
    // green
    $line = str_replace('[green]', "\033[0;32m", $line);
    // red
    $line = str_replace('[red]', "\033[0;31m", $line);
    // yellow
    $line = str_replace('[yellow]', "\033[0;33m", $line);
    // blue
    $line = str_replace('[blue]', "\033[0;34m", $line);
    // magenta
    $line = str_replace('[magenta]', "\033[0;35m", $line);
    // cyan
    $line = str_replace('[cyan]', "\033[0;36m", $line);
    //grey
    $line = str_replace('[grey]', "\033[0;90m", $line);
    // reset
    $line = str_replace('[reset]', "\033[0m", $line);
    // bold
    $line = str_replace('[bold]', "\033[1m", $line);
    // underline
    $line = str_replace('[underline]', "\033[4m", $line);
    // clear
    $line = str_replace('[clear]', "\033[2J", $line);


    printText($line . PHP_EOL);
    usleep(10000);
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

function clearScreen(): void
{
    printLine('[clear]');
}

function pressEnterToContinue(): void
{
    printLine(translate('Press enter to continue...'));
    readInput();
}

function main(): void
{
    $game = Game::getInstance();
    $game->start();
}
function translate(string $key): string
{
    $translations = include(__DIR__ . '/src/translations/' . LANG . '.php');
    return $translations[$key];
}

main();