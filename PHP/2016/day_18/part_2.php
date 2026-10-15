<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$rows = 400000;

$secures = substr_count($data[0], '.');

$mosaic = str_split($data[0]);
$size = count($mosaic);

for ($i = 1; $i < $rows; $i++) {
    $newMosaic = [];
    $mosaic[-1] = '.';
    $mosaic[$size] = '.';
    for ($j = 0; $j < $size; $j++) {
        if($mosaic[$j - 1] !== $mosaic[$j + 1]){
            $value = '^';
        }else{
            $secures++;
            $value = '.';
        }

        $newMosaic[$j] = $value;
    }
    $mosaic = $newMosaic;
}

echo 'Solution : '.$secures. PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;

