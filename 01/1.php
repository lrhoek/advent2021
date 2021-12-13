<?php
$input = file_get_contents('input');
$input = explode(PHP_EOL, $input);

$result =  array_reduce(
    $input,
    function ($state, $number) {
        if ($number > $state[1]) { $state[0]++; }
        $state[1] = $number;
        return $state;
    },
    [0,$input[0]]
);

echo $result[0].PHP_EOL;

for ($i = 0; $i < count($input)-2; $i++) {
    $denoised[$i] = $input[$i] + $input[$i+1] + $input[$i+2];
}

$result =  array_reduce(
    $denoised,
    function ($state, $number) {
        if ($number > $state[1]) { $state[0]++; }
        $state[1] = $number;
        return $state;
    },
    [0,$denoised[0]]
);

echo $result[0].PHP_EOL;