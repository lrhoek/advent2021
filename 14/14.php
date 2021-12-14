<?php

function map_insertion($insertion_rule) : array {
    list ($from, $to) = explode(' -> ', $insertion_rule);
    return [$from => [substr($from, 0, 1).$to, $to.substr($from, 1, 1), ]];
}

list ($template, $insertion_rules) = explode(PHP_EOL.PHP_EOL, file_get_contents('input'));
$insertion_rules = array_merge(...array_map(map_insertion(...), explode(PHP_EOL, $insertion_rules)));

$counts = array_fill_keys(array_keys($insertion_rules), 0);

foreach (range(0, strlen($template)-2) as $offset ) {
    $part = substr($template, $offset, 2);
    $counts[$part] += 1;
}

foreach (range(1, 40) as $step) {
    $newcounts = $counts;
    foreach ($insertion_rules as $out => $in) {
        $newcounts[$in[0]] += $counts[$out];
        $newcounts[$in[1]] += $counts[$out];
        $newcounts[$out] -= $counts[$out];
    }
    $counts = $newcounts;
}

$totals = array_fill_keys(array_map(fn ($key) => substr($key, 0, 1), array_keys($counts)), 0);
$keys = array_keys($counts);
$totals[substr(reset($keys), 0, 1)] = reset($counts);
foreach ($counts as $sequence => $amount) {
    $totals[substr($sequence, 1, 1)] += $amount;
}

sort($totals);
echo end($totals) - reset($totals).PHP_EOL;