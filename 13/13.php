<?php

function map_coordinate_to_grid($grid, $coordinate) : array {
    list ($x, $y) = explode(',', $coordinate);
    $grid[$y][$x] = true;
    return $grid;
}

function foldY($grid, $amount) : array {
    $top = array_filter($grid, fn ($key) => $key < $amount, ARRAY_FILTER_USE_KEY);
    $bottom = array_filter($grid, fn ($key) => $key > $amount, ARRAY_FILTER_USE_KEY);

    foreach ($bottom as $y => $row) {
        foreach (array_keys($row) as $x) {
            $top[2*$amount-$y][$x] ??= true;
        }
    }

    return $top;
}

function foldX($grid, $amount) : array {

    foreach ($grid as $y => $row) {

        $left = array_filter($row, fn ($key) => $key < $amount, ARRAY_FILTER_USE_KEY);
        $right = array_filter($row, fn ($key) => $key > $amount, ARRAY_FILTER_USE_KEY);

        foreach (array_keys($right) as $x) {
            $left[2*$amount-$x] ??= true;
        }

        $grid[$y] = $left;
    }

    return $grid;
}

list($coordinates, $instructions) = explode(PHP_EOL.PHP_EOL, file_get_contents('input'));

$grid = array_reduce(explode(PHP_EOL, $coordinates), map_coordinate_to_grid(...), []);

$result = array_reduce(
    array_map(fn ($instruction) => explode("=", str_replace("fold along ", "", $instruction)), explode(PHP_EOL, $instructions)),
    fn (array $grid, array $instruction) => reset($instruction) === "x" ? foldX($grid, end($instruction)) : foldY($grid, end($instruction)),
    $grid
);

foreach (range(min(array_keys($result)), max(array_keys($result))) as $y) {
    if (isset($result[$y])) {
        foreach (range(min(array_keys($result[$y])), max(array_keys($result[$y]))) as $x) {
            echo $result[$y][$x] ?? false ? "#" : " ";
        }
    }
    echo PHP_EOL;
}

