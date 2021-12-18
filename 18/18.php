<?php

function build_list(array $number, $depth = 1) {
    return array_reduce($number, fn ($carry, $value) => is_numeric($value) ? [...$carry, ["regular" => $value, "depth" => $depth]] : [...$carry, ...build_list($value, $depth+1)], []);
}

function explosions(array $number) : array {
    $start = array_values($number);
    foreach ($number as $index => $element) {
        if ($element["depth"] === 5) {
            if ($index-1 >= 0) {
                $number[$index-1]["regular"] += $element["regular"];
            }
            $number[$index]["depth"] = 4;
            $number[$index]["regular"] = 0;
            if ($index+2 < count($number)) {
                $number[$index+2]["regular"] += $number[$index+1]["regular"];
            }
            unset($number[$index+1]);
            break;
        }
    }
    $number = array_values($number);
    return $start === $number ? $number : explosions($number);
}

function splits(array $number) : array {
    $start = array_values($number);
    foreach ($number as $index => $element) {
        if ($element["regular"] >= 10) {
            $split = [
                ["depth" => $element["depth"]+1, "regular" => floor($element["regular"] /2)],
                ["depth" => $element["depth"]+1, "regular" => ceil($element["regular"] / 2)]
            ];
            array_splice($number, $index, 1, $split);
            break;
        }
    }
    $number = explosions(array_values($number));
    $number = array_values($number);
    return $start === $number ? $number : splits($number);

}

function add(array $left, array $right) : array {
    if (empty($left)) { return $right; }
    $new = array_merge($left, $right);
    foreach ($new as $index => $element) {
        $new[$index]["depth"]++;
    }
    $new = splits(explosions($new));
    return $new;
}

function magnitude(array $number) {
    if (count($number) === 1) { return reset($number)["regular"]; }
    $number = array_values($number);
    foreach ($number as $index => $element) {
        if ($element["depth"] === $number[$index+1]["depth"]) {
            $number[$index]["regular"] = $element["regular"] * 3 + $number[$index+1]["regular"] * 2;
            $number[$index]["depth"]--;
            unset($number[$index+1]);
            break;
        }
    }
    return magnitude(array_values($number));
}

function largest_sum(array $numbers) : int {
    $magnitude = 0;
    foreach ($numbers as $number) {
        foreach ($numbers as $number2) {
            if ($number === $number2) { continue; }
            $magnitude = max(magnitude(add($number, $number2)), $magnitude);
        }
    }
    return $magnitude;
}

$numbers = array_map(build_list(...), array_map(fn ($number) => eval("return ".$number.";"), explode(PHP_EOL, file_get_contents('input'))));

echo magnitude(array_reduce($numbers, add(...), [])).PHP_EOL;

echo largest_sum($numbers).PHP_EOL;