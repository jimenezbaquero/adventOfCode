<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$solution = 0;
$cells = [];

foreach ($data as $line) {
    $aux = explode(" @ ", $line);
    $aux = explode(": ", $aux[1]);
    $auxMargins = explode(",", $aux[0]);
    $auxSizes = explode("x", $aux[1]);
    for ($i = 0; $i < $auxSizes[0]; $i++) {
        for ($j = 0; $j < $auxSizes[1]; $j++) {
            if (isset($cells[$auxMargins[0] + $i][$auxMargins[1] + $j])) {
                if ($cells[$auxMargins[0] + $i][$auxMargins[1] + $j] == 1) {
                    $solution++;
                }
                $cells[$auxMargins[0] + $i][$auxMargins[1] + $j]++;
            } else {
                $cells[$auxMargins[0] + $i][$auxMargins[1] + $j] = 1;
            }
            
        }
    }
}

echo 'Solution : ' . $solution . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;

