<?php

$input = file_get_contents('input');
$input = explode(PHP_EOL, $input);

$position = array_reduce(
    $input,
    function ($position, $instruction) {
        list($operation, $amount) = explode(" ", $instruction);
        switch ($operation) {
            case 'forward':
                $position[0] += (int) $amount;
                break;
            case 'down':
                $position[1] += (int) $amount;
                break;
            case 'up':
                $position[1] -= (int) $amount;
                break;
        }
        return $position;
    },
    [0,0]
);

echo array_product($position).PHP_EOL;

$position = array_reduce(
    $input,
    function ($position, $instruction) {
        list($operation, $amount) = explode(" ", $instruction);
        switch ($operation) {
            case 'forward':
                $position[0] += (int) $amount;
                $position[1] += $position[2] * (int) $amount;
                break;
            case 'down':
                $position[2] += (int) $amount;
                break;
            case 'up':
                $position[2] -= (int) $amount;
                break;
        }
        return $position;
    },
    [0,0,0]
);
echo $position[0]*$position[1].PHP_EOL;