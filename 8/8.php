<?php

$input = file_get_contents('input');
$entries = explode(PHP_EOL, $input);

$entries = array_map(
    function ($entry) {
        list($signalPattern, $outputValue) = array_map(
            function($digits) {
                return explode(' ', $digits);
            },
            explode(' | ', $entry)
        );
        return ['signalPattern' => $signalPattern, 'outputValue' => $outputValue];
    },
    $entries
);

$outputDigits = array_reduce(
    $entries,
    function ($count, $entry) {
        $count += count(
            array_filter(
                $entry['outputValue'],
                function ($digit) {
                    return in_array(strlen($digit), [2,3,4,7]);
                }
            )
        );
        return $count;
    },
    0
);

echo $outputDigits.PHP_EOL;

function determineDigits($sequence) {

    $digits = [];
    $digits[1] = array_values(array_filter($sequence, fn ($digit) => strlen($digit) === 2))[0];
    $digits[4] = array_values(array_filter($sequence, fn ($digit) => strlen($digit) === 4))[0];
    $digits[7] = array_values(array_filter($sequence, fn ($digit) => strlen($digit) === 3))[0];
    $digits[8] = array_values(array_filter($sequence, fn ($digit) => strlen($digit) === 7))[0];
    $digits[3] = array_values(array_filter($sequence, fn ($digit) => strlen($digit) === 5 && contains($digits[1], $digit)))[0];
    $digits[9] = array_values(array_filter($sequence, fn ($digit) => strlen($digit) === 6 && contains($digits[3], $digit)))[0];
    $digits[5] = array_values(array_filter($sequence, fn ($digit) => strlen($digit) === 5 && contains($digit, $digits[9]) && $digit !== $digits[3]))[0];
    $digits[6] = array_values(array_filter($sequence, fn ($digit) => strlen($digit) === 6 && contains($digits[5], $digit) && $digit !== $digits[9]))[0];
    $digits[2] = array_values(array_filter($sequence, fn ($digit) => strlen($digit) === 5 && $digit !== $digits[3] && $digit !== $digits[5]))[0];
    $digits[0] = array_values(array_diff($sequence, $digits))[0];

    $digits = array_map(fn ($digit) => str_sort($digit), $digits);

    return array_flip($digits);
}

function contains(string $needle, string $haystack) {
    return strlen($needle) == count(array_intersect(str_split($needle), str_split($haystack)));
}

function str_sort(string $string) {
    $strArray = str_split($string);
    sort($strArray);
    return implode($strArray);
}

$outputDigits = array_reduce(
    $entries,
    function ($count, $entry) {
        $digits = determineDigits($entry['signalPattern']);
        return $count + (int) array_reduce(
            $entry['outputValue'],
            function ($output, $outputDigit) use ($digits) {
                return $output . $digits[str_sort($outputDigit)];
            },
            ""
        );
    },
    0
);

echo $outputDigits.PHP_EOL;
