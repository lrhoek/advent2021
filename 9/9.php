<?php

$input = file_get_contents('input');
$rows = explode(PHP_EOL, $input);

$grid = array_map(fn ($row) => str_split($row), $rows);

$riskLevels = 0;

foreach (array_keys($grid) as $row) {
    foreach (array_keys(reset($grid)) as $column) {
        $lowPoint = true;
        $lowPoint &= $grid[$row][$column] < ($grid[$row-1][$column] ?? $grid[$row][$column]+1);
        $lowPoint &= $grid[$row][$column] < ($grid[$row+1][$column] ?? $grid[$row][$column]+1);
        $lowPoint &= $grid[$row][$column] < ($grid[$row][$column-1] ?? $grid[$row][$column]+1);
        $lowPoint &= $grid[$row][$column] < ($grid[$row][$column+1] ?? $grid[$row][$column]+1);
        if ($lowPoint) {
            $riskLevels += $grid[$row][$column] + 1;
        }
    }
}

echo $riskLevels.PHP_EOL;

foreach (array_keys($grid) as $row) {
    foreach (array_keys(reset($grid)) as $column) {
        $lowPoint = true;
        $lowPoint &= $grid[$row][$column] < ($grid[$row-1][$column] ?? $grid[$row][$column]+1);
        $lowPoint &= $grid[$row][$column] < ($grid[$row+1][$column] ?? $grid[$row][$column]+1);
        $lowPoint &= $grid[$row][$column] < ($grid[$row][$column-1] ?? $grid[$row][$column]+1);
        $lowPoint &= $grid[$row][$column] < ($grid[$row][$column+1] ?? $grid[$row][$column]+1);
        if ($lowPoint) {
            $lowPoints[] = [$row, $column];
        }
    }
}

function getBassin($points, $grid) {

    $newPoints = [];

    foreach ($points as $point) {

        if (isset($grid[$point[0]-1][$point[1]]) && $grid[$point[0]-1][$point[1]] != 9 && !in_array([$point[0]-1, $point[1]], array_merge($points, $newPoints))) {
            $newPoints[] = [$point[0]-1, $point[1]];
        }
        if (isset($grid[$point[0]+1][$point[1]]) && $grid[$point[0]+1][$point[1]] != 9 && !in_array([$point[0]+1, $point[1]], array_merge($points, $newPoints))) {
            $newPoints[] = [$point[0]+1, $point[1]];
        }
        if (isset($grid[$point[0]][$point[1]-1]) && $grid[$point[0]][$point[1]-1] != 9 && !in_array([$point[0], $point[1]-1], array_merge($points, $newPoints))) {
            $newPoints[] = [$point[0], $point[1]-1];
        }
        if (isset($grid[$point[0]][$point[1]+1]) && $grid[$point[0]][$point[1]+1] != 9 && !in_array([$point[0], $point[1]+1], array_merge($points, $newPoints))) {
            $newPoints[] = [$point[0], $point[1]+1];
        }
    }

    if (empty($newPoints)) {
        return $points;
    }

    else {
        return getBassin(array_merge($points, $newPoints), $grid);
    }
}

$bassins = array_map(fn ($bassin) => getBassin([$bassin], $grid), $lowPoints);

$bassinSizes = array_map(fn ($bassin) => count($bassin), $bassins);

rsort($bassinSizes);

$total = array_product(array_slice($bassinSizes, 0, 3));

echo $total.PHP_EOL;
