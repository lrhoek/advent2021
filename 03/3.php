<?php

$input = file_get_contents('input');

$numbers = explode("\n", $input);

$listLength = count($numbers);
$numberLength = strlen(reset($numbers));

$numbers = array_map(
    function ($row) {
        return str_split($row);
    },
    $numbers
);

for ($i = 0; $i < $numberLength; $i++) {
    $mostCommon[$i] = (int) (array_sum(array_column($numbers, $i)) > $listLength / 2);
    $leastCommon[$i] = (int) !$mostCommon[$i];
}

$gammaRate = bindec(implode($mostCommon));
$epsilonRate = bindec(implode($leastCommon));

$powerConsumption = $gammaRate * $epsilonRate;

echo $powerConsumption.PHP_EOL;

$oxygenGeneratorNumbers = $numbers;
for ($i = 0; $i < $numberLength; $i++) {
    $mostCommon[$i] = (int) (array_sum(array_column($oxygenGeneratorNumbers, $i)) >= count($oxygenGeneratorNumbers) / 2);
    $oxygenGeneratorNumbers = array_filter(
        $oxygenGeneratorNumbers,
        function ($number) use ($i, $mostCommon) {
            return (int) $number[$i] === $mostCommon[$i];
        }
    );

    if (count($oxygenGeneratorNumbers) === 1) { break; }
}

$co2ScrubberRatingNumbers = $numbers;
for ($i = 0; $i < $numberLength; $i++) {
    $leastCommon[$i] = (int) (array_sum(array_column($co2ScrubberRatingNumbers, $i)) < count($co2ScrubberRatingNumbers) / 2);
    $co2ScrubberRatingNumbers = array_filter(
        $co2ScrubberRatingNumbers,
        function ($number) use ($i, $leastCommon) {
            return (int) $number[$i] === $leastCommon[$i];
        }
    );

    if (count($co2ScrubberRatingNumbers) === 1) { break; }
}

$oxygenGeneratorRating = bindec(implode(reset($oxygenGeneratorNumbers)));
$co2ScrubberRating = bindec(implode(reset($co2ScrubberRatingNumbers)));

$lifeSupportRating = $oxygenGeneratorRating * $co2ScrubberRating;

echo $lifeSupportRating.PHP_EOL;