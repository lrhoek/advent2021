<?php

$input = file_get_contents('input');
$input = explode(PHP_EOL, $input);

$lines = array_map(
    function ($line) {
        list($start, $end) = explode(' -> ', $line);
        list($startX, $startY) = explode(',', $start);
        list($endX, $endY) = explode(',', $end);
        return [
            'startX' => $startX,
            'endX' => $endX,
            'startY' => $startY,
            'endY' => $endY
        ];
    },
    $input
);

$hvLines = array_filter(
    $lines,
    function ($line) {
        return $line['startX'] === $line['endX'] || $line['startY'] === $line['endY'];
    }
);

$grid = array_reduce(
    $hvLines,
    function ($grid, $line) {
        foreach(range($line['startX'], $line['endX']) as $x) {
            foreach(range($line['startY'], $line['endY']) as $y) {
                $grid[$x][$y] = isset($grid[$x][$y]) ? $grid[$x][$y] + 1 : 1;
            }
        }
        return $grid;
    },
    []
);

$points = call_user_func_array('array_merge', $grid);

$score = array_filter(
    $points,
    function ($point) {
        return $point > 1;
    }
);

echo count($score).PHP_EOL;

$dLines = array_filter(
    $lines,
    function ($line) {
        return !($line['startX'] === $line['endX'] || $line['startY'] === $line['endY']);
    }
);

$grid = array_reduce(
    $dLines,
    function ($grid, $line) {
        $yRange = range($line['startY'], $line['endY']);
        foreach (range($line['startX'], $line['endX']) as $index => $x) {
            $grid[$x][$yRange[$index]] = isset($grid[$x][$yRange[$index]]) ? $grid[$x][$yRange[$index]] + 1 : 1;
        }
        return $grid;
    },
    $grid
);

$points = call_user_func_array('array_merge', $grid);

$score = array_filter(
    $points,
    function ($point) {
        return $point > 1;
    }
);

echo count($score).PHP_EOL;
