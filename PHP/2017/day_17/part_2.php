<?php

$start = microtime(true);

require_once('../../functions.php');

//$amount = 3;
$amount = 377;

$index = 0;
$solution = 0;

for ($i = 1; $i <= 50000000; $i++) {
    $index = (($index + $amount) % $i) + 1;
    if($index == 1) {
        $solution = $i;
    }
}

echo 'Solution : ' . $solution . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;


