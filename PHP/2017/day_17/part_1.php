<?php

$start = microtime(true);

require_once('../../functions.php');

$amount = 3;
//$amount = 377;

$values = [0, 1];
$index = 1;


for ($i = 2; $i <= 2017; $i++) {
    $index = (($index + $amount) % $i) + 1;
    for($j = $i; $j > $index; $j--) {
        $values[$j] = $values[$j - 1];
    }
    $values[$index] = $i;
}

$solution = $values[$index + 1];

echo 'Solution : ' . $solution . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;


