<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$solution = '';
$size = 256;

$chars = str_split($data[0]);
$aux = [];

foreach ($chars as $char) {
    $aux [] = ord($char);
}

$movements = array_merge($aux, [17, 31, 73, 47, 23]);

$skip = 0;
$pos = 0;
$grid = [];
for($i = 0; $i < $size; $i++) {
    $grid[] = $i;
}

for ($j = 0; $j < 64; $j++) {
    foreach ($movements as $char) {
        $movement = intval($char);
        $aux = $grid;
        for ($i = 0; $i < $char; $i++) {
            $aux[($pos + $char - 1 - $i) % $size] = $grid[($pos + $i) % $size];
        }

        $grid = $aux;
        $pos = ($pos + $char + $skip) % $size;
        $skip++;
    }
}

$result = 0;
for ($i = 0; $i < $size; $i++) {
    $result ^= $grid[$i];
    if( $i % 16 == 15){
        $solution .= str_pad(dechex($result), 2, '0', STR_PAD_LEFT);
        $result = 0;
    }
}


echo 'Solution : '.$solution.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start, 2).PHP_EOL;
echo 'Memory used: '.number_format(memory_get_peak_usage() / 1024 / 1024, 2).' MB'.PHP_EOL;
echo 'Memory reserved: '.number_format(memory_get_peak_usage(true) / 1024 / 1024, 2).' MB'.PHP_EOL;
