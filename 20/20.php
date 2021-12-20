<?php

function image(string $image) : array {
    return array_map(str_split(...), explode(PHP_EOL, $image));
}

function pixelgroup(array $image, int $x, int $y, string $infinity_pixel) : string {
    return str_replace(
        ['#', '.'], [1,0],
        ($image[$x - 1][$y - 1] ?? $infinity_pixel).
        ($image[$x-1][$y] ?? $infinity_pixel).
        ($image[$x-1][$y+1] ?? $infinity_pixel).
        ($image[$x][$y-1] ?? $infinity_pixel).
        ($image[$x][$y] ?? $infinity_pixel).
        ($image[$x][$y+1] ?? $infinity_pixel).
        ($image[$x+1][$y-1] ?? $infinity_pixel).
        ($image[$x+1][$y] ?? $infinity_pixel).
        ($image[$x+1][$y+1] ?? $infinity_pixel)
    );
}

function enhance(array $image, string $algorithm, int $iteration) : array {

    $infinity_pixel = infinity_pixel($iteration, $algorithm);

    $image = array_map(fn ($row) => str_split($infinity_pixel.$infinity_pixel.implode("", $row).$infinity_pixel.$infinity_pixel), $image);
    array_unshift($image, array_fill(0, count($image[0]), $infinity_pixel));
    array_unshift($image, array_fill(0, count($image[0]), $infinity_pixel));
    $image[] = array_fill(0, count($image[0]), $infinity_pixel);
    $image[] = array_fill(0, count($image[0]), $infinity_pixel);

    $enhanced = $image;

    foreach ($image as $x => $row) {
        foreach ($row as $y => $value) {
            $enhanced[$x][$y] = substr($algorithm, base_convert(pixelgroup($image, $x, $y, $infinity_pixel), 2, 10), 1);
        }
    }

    return $enhanced;
}

function infinity_pixel(int $iterations, string $algorithm) : string {
    return array_reduce(
        range(0, $iterations),
        fn($pixel, $iteration) => substr($algorithm, base_convert(str_replace(['#', '.'], [1, 0], str_pad("", 9, $pixel)), 2, 10), 1),
        '.'
    );
}

function enhance_times(string $image, string $algorithm, int $iterations) {
    return array_reduce(
        range(1,$iterations),
        fn ($image, $iteration) => enhance($image, $algorithm, $iteration),
        image($image)
    );
}

function count_light_pixels(array $image) : int {
    $image = implode("", array_map(fn ($row) => implode("", $row), $image));
    return substr_count($image, "#");
}

list($algorithm, $image) = explode(PHP_EOL.PHP_EOL, file_get_contents('input'));

echo count_light_pixels(enhance_times($image, $algorithm, 2)).PHP_EOL;
echo count_light_pixels(enhance_times($image, $algorithm, 50)).PHP_EOL;