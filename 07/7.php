<?php

$input = file_get_contents('input');
$crabs = explode(',', $input);

$swarm = array_reduce(
    $crabs,
    function ($swarm, $crab) {
        isset($swarm[(int) $crab]) ? $swarm[(int) $crab]++ : $swarm[(int) $crab] = 1;
        return $swarm;
    },
    []
);

$possiblePositions = range(min($crabs), max($crabs));
$fuelCosts = array_map(fn ($position) => 0, $possiblePositions);

foreach ($possiblePositions as $possiblePosition) {
    foreach ($swarm as $position => $amount) {
        $fuelCosts[$possiblePosition] += abs($position - $possiblePosition) * $amount;
    }
}

echo min($fuelCosts).PHP_EOL;

$fuelCosts = array_map(fn ($position) => 0, $possiblePositions);

foreach ($possiblePositions as $possiblePosition) {
    foreach ($swarm as $position => $amount) {
        $diff = abs($position - $possiblePosition);
        $fuelCosts[$possiblePosition] += ($diff + 1) * $diff/2 * $amount;
    }
}

echo min($fuelCosts).PHP_EOL;