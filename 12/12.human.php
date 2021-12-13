<?php

function map_connection(string $connection) : array {
    $caves = explode('-', $connection);
    return [$caves[0] => [$caves[1]], $caves[1] => [$caves[0]]];
}

function allowed_caves(array $caves, array $visited, bool $extended_rules) : array {
    return array_values($extended_rules ? array_filter($caves, fn ($cave) => extended_rules($cave, $visited)) : array_filter($caves, fn ($cave) => simple_rules($cave, $visited)));
}

function simple_rules(string $cave, array $visited) : bool {
    return !ctype_lower($cave) || !in_array($cave, $visited);
}
function extended_rules(string $cave, array $visited) : bool {
    return !($cave === "start" || ctype_lower($cave) && in_array(2, array_count_values(array_filter($visited, ctype_lower(...)))) && in_array($cave, $visited));
}

function get_number_of_paths($cave_system, $extended_rules = false, $start = 'start', $end = 'end', $visited = []) : int {
    $visited[] = $start;
    return $start === $end ? 1 : array_reduce(allowed_caves($cave_system[$start], $visited, $extended_rules), fn ($total, $next) => $total + get_number_of_paths($cave_system, $extended_rules, $next, $end, $visited), 0);
}

$connections = explode(PHP_EOL, file_get_contents('input'));
$cave_system = array_merge_recursive(...array_map(map_connection(...), $connections));

echo get_number_of_paths($cave_system).PHP_EOL;
echo get_number_of_paths($cave_system, true).PHP_EOL;