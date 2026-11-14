<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$solution = 0;

$points = [];

foreach ($data as $point) {
    $aux = explode(",", $point);
    $points[] = $aux;
}

$constellations = [];

foreach ($points as $point) {
    foreach ($constellations as $key => $constellation) {
        foreach ($constellation as $star) {
            if (calculateDistance($star, $point) <= 3) {
                $constellations[$key][] = $point;
                continue 3;
            }
        }
    }
    $constellations[] = [$point];
}

$mustContinue = true;

while ($mustContinue) {
    $mustContinue = false;
    $process = [];
    foreach ($constellations as $key1 => $constellation1) {
        $process[$key1] = true;
        foreach ($constellations as $key2 => $constellation2) {
            if (isset($process[$key2])) {
                continue;
            }
            foreach ($constellation1 as $star1) {
                foreach ($constellation2 as $star2) {
                    if (calculateDistance($star1, $star2) <= 3) {
                        foreach ($constellation2 as $star3) {
                            $constellations[$key1][] = $star3;
                        }
                        unset($constellations[$key2]);
                        $mustContinue = true;
                        break 4;
                    }
                }
            }
        }
    }
}
 $solution = count($constellations);

echo 'Solution : ' . $solution . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;

function calculateDistance($point1, $point2) {
    $distance = 0;
    foreach (array_keys($point1) as $key) {
        $distance += abs($point1[$key] - $point2[$key]);
    }
    return $distance;
}