<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$solution = 0;
$size = 256;
$skip = 0;
$pos = 0;
$grid = [];

for($i = 0; $i < $size; $i++) {
    $grid[] = $i;
}
$movements = explode(',', $data[0]);

foreach ($movements as $movement) {
    $movement = intval($movement);
    $aux = $grid;
    for ($i = 0; $i < $movement; $i++) {
        $aux[($pos + $movement - 1 - $i) % $size] = $grid[($pos + $i) % $size];
    }

    $grid = $aux;
    $pos = ($pos + $movement + $skip) % $size;
    $skip++;
}

$solution = $grid[0] * $grid[1];

echo 'Solution : '.$solution.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start, 2).PHP_EOL;
echo 'Memory used: '.number_format(memory_get_peak_usage() / 1024 / 1024, 2).' MB'.PHP_EOL;
echo 'Memory reserved: '.number_format(memory_get_peak_usage(true) / 1024 / 1024, 2).' MB'.PHP_EOL;
