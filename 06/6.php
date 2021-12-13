<?php

$input = file_get_contents('input');
$fish = explode(',', $input);

$fish = array_map(fn($fishie) => (int) $fishie, $fish);

foreach (range(1,80) as $day) {
    foreach ($fish as $fishNumber => $fishie) {
        if ($fishie === 0) {
            $fish[] = 8;
            $fish[$fishNumber] = 6;
        }
        else {
            $fish[$fishNumber]--;
        }
    }
}

echo count($fish).PHP_EOL;

$input = file_get_contents('input');
$fish = explode(',', $input);

$fish = array_map(fn($fishie) => (int) $fishie, $fish);

$school = array_reduce(
    $fish,
    function ($school, $fishie) {
        $school[$fishie]++;
        return $school;
    },
    [0,0,0,0,0,0,0,0,0]
);

$school = array_reduce(
    range(1,256),
    function ($school, $day) {
        $today = array_shift($school);
        $school[6] += $today;
        $school[8] = $today;
        return $school;
    },
    $school
);

echo array_sum($school).PHP_EOL;
