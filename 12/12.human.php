<?php

function map_connection(string $connection) : array {
    list($source, $target) = explode('-', $connection);
    return [$source => [$target], $target => [$source]];
}

function allowed_caves(array $caves, array $visited, int $maximum_single_small_cave_revisits) : array {
    return array_filter($caves, fn ($cave) => cave_allowed($cave, $maximum_single_small_cave_revisits, $visited));
}

function cave_allowed($cave, $maximum_single_small_cave_revisits, $visited) : bool{
    return !($cave === "start" || ctype_lower($cave) && in_array($maximum_single_small_cave_revisits, small_cave_amounts($visited)) && in_array($cave, $visited));
}

function small_cave_amounts(array $caves) : array {
    return array_count_values(array_filter($caves, ctype_lower(...)));
}

function get_number_of_paths($cave_system, $maximum_single_small_cave_revisits = 1, $start = 'start', $end = 'end', $visited = []) : int {
    $visited[] = $start;
    $allowed = allowed_caves($cave_system[$start], $visited, $maximum_single_small_cave_revisits);
    return $start === $end ? 1 : array_reduce($allowed, fn ($total, $next) => $total + get_number_of_paths($cave_system, $maximum_single_small_cave_revisits, $next, $end, $visited), 0);
}

$connections = explode(PHP_EOL, file_get_contents('input'));
$cave_system = array_merge_recursive(...array_map(map_connection(...), $connections));

echo get_number_of_paths($cave_system).PHP_EOL;
echo get_number_of_paths($cave_system, 2).PHP_EOL;