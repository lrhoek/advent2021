<?php

function filter_valid_chunks($line) {

    $combinations = ['()', '{}', '[]', '<>'];
    $line = str_replace($combinations, '', $line, $count);
    return ($count === 0 ? $line : filter_valid_chunks($line));

}

function complete($line) {

    return array_reduce([')', '}', ']', '>'], fn ($count, $closer) => $count + substr_count($line, $closer)) > 0;

}

function find_closer($line) {

    $positions = array_map(fn ($character) => strpos($line, $character), [')', '}', ']', '>']);
    $positions = array_filter($positions);
    return substr($line, min($positions), 1);

}

function score($line) {

    return array_reduce(
        str_split($line),
        function ($totalScore, $character) {
            return $totalScore * 5 + [')' => 1, '}' => 3, ']' => 2, '>' => 4][$character];
        },
        0
    );

}

$lines = explode(PHP_EOL, file_get_contents('input'));

$lines = array_map('filter_valid_chunks', $lines);

$lines = array_filter($lines, 'complete');

$lines = array_map('find_closer', $lines);

$score = array_sum(array_map(fn ($closer) => [')' => 3, '}' => 1197, ']' => 57, '>' => 25137][$closer], $lines));

echo $score.PHP_EOL;

$lines = explode(PHP_EOL, file_get_contents('input'));

$lines = array_map('filter_valid_chunks', $lines);

$lines = array_filter($lines, fn ($line) => !complete($line));

$lines = array_map(fn ($line) => str_replace(['(', '{', '[', '<'], [')', '}', ']', '>'], strrev($line)), $lines);

$lines = array_map('score', $lines);

sort($lines);

echo $lines[intdiv(count($lines), 2)].PHP_EOL;

