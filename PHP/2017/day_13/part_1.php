<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$solution = 0;
$layers = [];

foreach ($data as $line) {
    $aux = explode(': ', $line);
    $size = intval($aux[1]);
    $period = $size == 1 ? 1 : 2 * ($size - 1);
    $layers[$aux[0]] = [
        'period' => $period,
        'size' => $size,
    ];
}

foreach ($layers as $number => $layer) {
    if ($number % $layer['period'] == 0) {
        $solution += $layer['size'] * $number;
    }
}


echo 'Solution : ' . $solution . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;


