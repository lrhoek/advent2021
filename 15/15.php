<?php

function map_grid(string $input) : array {
    return array_map(str_split(...), explode(PHP_EOL, $input));
}

function available_paths(array $location) : array {
    list ($x, $y) = $location;
    return [[$x-1, $y], [$x+1, $y], [$x, $y-1], [$x, $y+1]];
}

function shortest_path(array $grid, array $destination, ?SplPriorityQueue $paths = null, array $visited = []) : int {

    while ($current = $paths->extract()) {
        $distance = abs($current["priority"]);
        $current = $current["data"];
        if ($current === $destination) { break; }
        list ($x, $y) = $current;
        if (isset($visited[$x][$y])) { continue; }
        $visited[$x][$y] = true;
        foreach (available_paths($current) as $available_path) {
            list ($x, $y) = $available_path;
            if (isset($grid[$x][$y])) {
                $paths->insert($available_path, -$grid[$x][$y]-$distance);
            }
        }
    }

    return $distance;
}

function enlarge(array $grid, int $enlargements = 4) : array {
    $grid = array_map(fn ($row) => enlarge_row($row, $enlargements), $grid);
    $copy = $grid;
    for($i = 0; $i < $enlargements; $i++) {
        $copy = array_map(fn($row) => array_map(enlarge_risk_level(...), $row), $copy);
        array_push($grid, ...$copy);
    }
    return $grid;
}

function enlarge_row(array $row, int $enlargements) : array {
    $copy = $row;
    for($i = 0; $i < $enlargements; $i++) {
        $copy = array_map(enlarge_risk_level(...), $copy);
        array_push($row, ...$copy);
    }
    return $row;
}

function enlarge_risk_level(int $risk_level) : int {
    return $risk_level === 9 ? 1 : $risk_level+1;
}

function init_paths() : SplPriorityQueue {
    $paths = new SplPriorityQueue();
    $paths->setExtractFlags(SplPriorityQueue::EXTR_BOTH);
    $paths->insert([0,0], 0);
    return $paths;
}

function init_destination($grid) : array {
    return [count($grid)-1, count(reset($grid))-1];
}

$grid = map_grid(file_get_contents('input'));
echo shortest_path($grid, init_destination($grid), init_paths()).PHP_EOL;

$grid = enlarge($grid);
echo shortest_path($grid, init_destination($grid), init_paths()).PHP_EOL;

