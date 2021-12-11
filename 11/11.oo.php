<?php

class Cavern {
    private array $grid = [[]];

    private string $input;

    public function __construct(string $input) {
        $this->input = $input;
        $this->reset();
    }

    public function reset() : void {
        $grid = explode(PHP_EOL, $this->input);
        $this->grid = array_map($this->map_row(...), $grid, array_keys($grid));
    }

    private function map_row($line, $x) : array {
        $row = str_split($line);
        return array_map(fn ($energy, $y) => new Octopus($x, $y, (int) $energy, $this), $row, array_keys($row));
    }

    private function flat() : array {
        return array_merge(...$this->grid);
    }

    public function neighbours(Octopus $octopus) : array {
        return array_filter([
            $this->grid[$octopus->x-1][$octopus->y-1] ?? null,
            $this->grid[$octopus->x-1][$octopus->y] ?? null,
            $this->grid[$octopus->x-1][$octopus->y+1] ?? null,
            $this->grid[$octopus->x][$octopus->y-1] ?? null,
            $this->grid[$octopus->x][$octopus->y+1] ?? null,
            $this->grid[$octopus->x+1][$octopus->y-1] ?? null,
            $this->grid[$octopus->x+1][$octopus->y] ?? null,
            $this->grid[$octopus->x+1][$octopus->y+1] ?? null
        ]);
    }

    public function step($amount = 1) : int {
        $flashes = array_reduce($this->flat(), fn (int $flashes, Octopus $octopus) => $flashes + $octopus->step(), 0);
        array_map(fn (Octopus $octopus) => $octopus->reset(), $this->flat());
        return $amount === 1 ? $flashes : $flashes + $this->step($amount - 1);
    }

    public function all($steps = 1) : int {
        return $this->step() === count($this->flat()) ? $steps : $this->all($steps +1);
    }
}

class Octopus {

    public int $x;
    public int $y;
    public int $energy;
    public Cavern $cavern;

    function __construct(int $x, int $y, int $energy, Cavern $cavern) {
        $this->x = $x;
        $this->y = $y;
        $this->energy = $energy;
        $this->cavern = $cavern;
    }

    function reset() : void
    {
        $this->energy !== 10 ?: $this->energy = 0;
    }

    public function step() : int {
        if ($this->energy === 9) {
            $this->energy++;
            return array_reduce($this->cavern->neighbours($this), fn (int $flashes, Octopus $octopus) => $flashes + $octopus->step(), 0) + 1;
        }
        elseif ($this->energy < 9) {
            $this->energy++;
        }

        return 0;
    }
}

$cavern = new Cavern(file_get_contents('input'));
echo $cavern->step(100).PHP_EOL;

$cavern->reset();
echo $cavern->all().PHP_EOL;