<?php

$input = file_get_contents('input');
$rows = explode(PHP_EOL, $input);

$grid = array_map(fn ($row) => str_split($row), $rows);

$riskLevels = 0;

foreach (array_keys($grid) as $row) {
    foreach (array_keys(reset($grid)) as $column) {
        $point = [$row, $column];
        $isLowPoint = array_reduce(
            getNeighbours($point),
            function ($isLowPoint, $neighbour) use ($grid, $point) {
                return $isLowPoint && ($grid[$neighbour[0]][$neighbour[1]] ?? 9) > $grid[$point[0]][$point[1]];
            },
            true
        );

        if ($isLowPoint) {
            $riskLevels += $grid[$row][$column] + 1;
            $lowPoints[] = $point;
        }
    }
}

echo $riskLevels.PHP_EOL;

function getNeighbours(mixed $point): array
{
    return [
        [$point[0] - 1, $point[1]],
        [$point[0] + 1, $point[1]],
        [$point[0], $point[1] - 1],
        [$point[0], $point[1] + 1]
    ];
}

function getBassin($points, $grid) {

    $newPoints = [];

    foreach ($points as $point) {

        $neighbours = getNeighbours($point);

        foreach ($neighbours as $neighbour) {
            if (($grid[$neighbour[0]][$neighbour[1]] ?? 9) != 9 && !in_array($neighbour, array_merge($points, $newPoints))) {
                $newPoints[] = $neighbour;
            }
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
