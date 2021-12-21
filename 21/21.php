<?php

function map_player($input) : int {
    preg_match('/Player \d starting position: (\d)/', $input, $player);
    return $player[1];
}

function init_universe($players) : array {
    return [
        "a" => ["position" => $players[0], "score" => 0],
        "b" => ["position" => $players[1], "score" => 0]
    ];
}

function init_d100() : Iterator {
    $dice = new InfiniteIterator(new ArrayIterator(range(1,100)));
    $dice->rewind();
    return $dice;
}

function practice($universe) {
    $dice = init_d100();
    $rolls = 0;
    $current = "a";

    while (true) {
        $rolls += 3;
        $points = $dice->current();
        $dice->next();
        $points += $dice->current();
        $dice->next();
        $points += $dice->current();
        $dice->next();
        $points = ($universe[$current]["position"] + $points) % 10;
        $universe[$current]["position"] = $points === 0 ? 10 : $points;
        $universe[$current]["score"] += $universe[$current]["position"];
        if ($universe[$current]["score"] >= 1000) break;
        $current = $current === "a" ? "b" : "a";
    }

    $current = $current === "a" ? "b" : "a";
    return $universe[$current]["score"] * $rolls;
}

function roll_distribution() : array {
    $result = [];
    foreach (range(1,3) as $roll1) {
        foreach (range(1, 3) as $roll2) {
            foreach (range(1, 3) as $roll3) {
                $result[] = array_sum([$roll1, $roll2, $roll3]);
            }
        }
    }
    return array_count_values($result);
}

function dirac(array $universe, $current, array $roll_distribution, $roll = 0) : array {
    $win_count = ["a" => 0, "b" => 0];

    if ($roll > 0) {
        $universe[$current]["position"] += $roll;
        $universe[$current]["position"] = $universe[$current]["position"] % 10 === 0 ? 10 : $universe[$current]["position"] % 10;
        $universe[$current]["score"] += $universe[$current]["position"];

        if ($universe[$current]["score"] >= 21) {
            $win_count[$current]++;
            return $win_count;
        }
    }

    foreach ($roll_distribution as $roll => $amount) {
        $result = dirac($universe, $current === "a" ? "b" : "a", $roll_distribution, $roll);
        foreach ($result as $winner => $wins) {
            $win_count[$winner] += $amount * $wins;
        }
    }

    return $win_count;
}

$players = array_map(map_player(...), explode(PHP_EOL, file_get_contents('input')));

$result = practice(init_universe($players));
echo $result.PHP_EOL;

$result = dirac(init_universe($players), "b", roll_distribution());
echo $result["a"].PHP_EOL;
echo $result["b"].PHP_EOL;