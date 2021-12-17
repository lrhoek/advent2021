<?php

function triangle(int $n) : int {
    return $n / 2 * ($n+1);
}

function max_height(int $y_start, int $y_end) : int {
    return triangle(abs(min(range($y_start, $y_end)))-1);
}

function options_count(int $x_start, int $x_end, int $y_start, int $y_end) : int
{
    $target_area_y = range($y_start, $y_end);
    $target_area_x = range($x_start, $x_end);
    $count = 0;
    foreach (range(min($target_area_y), abs(min($target_area_y))) as $start_velocity_y) {
        foreach (range(0, max($target_area_x)) as $start_velocity_x) {
            $position_x = 0;
            $position_y = 0;
            $velocity_x = $start_velocity_x;
            $velocity_y = $start_velocity_y;
            while ($position_x <= max($target_area_x) && $position_y >= min($target_area_y)) {
                $position_x += $velocity_x;
                $position_y += $velocity_y;
                if (in_array($position_x, $target_area_x) && in_array($position_y, $target_area_y)) {
                    $count++;
                    break;
                }
                $velocity_x === 0 ?: $velocity_x--;
                $velocity_y--;
            }
        }
    }
    return $count;
}

preg_match("/target area: x=(-?\d+)..(-?\d+), y=(-?\d+)..(-?\d+)/", file_get_contents('input'), $input);

echo max_height($input[3], $input[4]).PHP_EOL;
echo options_count($input[1], $input[2], $input[3], $input[4]).PHP_EOL;