<?php

class Octopus {

    public int $y;
    public int $energy;
    public array $grid;
    public int $x;

    function __construct(int $x, int $y, int $energy) {
        $this->x = $x;
        $this->y = $y;
        $this->energy = $energy;
    }

    function set_grid(array &$grid) {
        $this->grid = &$grid;
    }

    function neighbours() : array {
        return array_filter([
            $this->grid[$this->x-1][$this->y-1] ?? null,
            $this->grid[$this->x-1][$this->y] ?? null,
            $this->grid[$this->x-1][$this->y+1] ?? null,
            $this->grid[$this->x][$this->y-1] ?? null,
            $this->grid[$this->x][$this->y] ?? null,
            $this->grid[$this->x][$this->y+1] ?? null,
            $this->grid[$this->x+1][$this->y-1] ?? null,
            $this->grid[$this->x+1][$this->y] ?? null,
            $this->grid[$this->x+1][$this->y+1] ?? null
        ]);
    }

    function increase_energy() : void
    {
        if ($this->energy === 9) {
            $this->energy++;
            $neighbours = $this->neighbours();
            array_walk($neighbours, fn (Octopus $octopus) => $octopus->increase_energy());
        }
        elseif ($this->energy < 9) {
            $this->energy++;
        }
    }

    function reset_flash() : bool
    {
        if ($this->energy === 10) {
            $this->energy = 0;
            return true;
        }
        return false;
    }
}

function map_row($line, $x) : array {
    $row = str_split($line);
    return array_map(fn ($energy, $y) => new Octopus($x, $y, (int) $energy), $row, array_keys($row));
}

function step($grid) : int {
    array_walk_recursive($grid, fn (Octopus $octopus) => $octopus->increase_energy());
    return array_sum(array_map(fn (Octopus $octopus) => (int) $octopus->reset_flash(), array_merge(...$grid)));
}

function find_steps_to_flash_amount($grid, $amount, $step = 1) : int {
    return step($grid) === $amount ? $step : find_steps_to_flash_amount($grid, $amount, $step +1);
}

function get_a_grid($input) {
    $grid = explode(PHP_EOL, $input);
    $grid = array_map(map_row(...), $grid, array_keys($grid));
    array_walk_recursive($grid, fn (Octopus $octopus) => $octopus->set_grid($grid));
    return $grid;
}

$grid = get_a_grid(file_get_contents('input'));

$flashes = array_reduce(range(1,100), fn ($carry, $step) => $carry + step($grid), 0);

echo $flashes.PHP_EOL;

$steps = find_steps_to_flash_amount($grid, count(array_merge(...$grid)),101);

echo $steps.PHP_EOL;