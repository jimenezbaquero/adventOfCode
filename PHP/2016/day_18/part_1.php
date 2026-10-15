<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$rows = 40;

$secures = substr_count($data[0], '.');

$mosaics[0] = str_split($data[0]);
$size = count($mosaics[0]);

for ($i = 1; $i < $rows; $i++) {
    $mosaic = $mosaics[$i - 1];
    $newMosaic = [];
    $mosaic[-1] = '.';
    $mosaic[$size] = '.';
    for ($j = 0; $j < $size; $j++) {
        if ($mosaic[$j - 1] == '^' && $mosaic[$j] == '^' && $mosaic[$j + 1] == '.') {
            $value = '^';
        }else if ($mosaic[$j - 1] == '.' && $mosaic[$j] == '^' && $mosaic[$j + 1] == '^') {
            $value = '^';
        }else if ($mosaic[$j - 1] == '^' && $mosaic[$j] == '.' && $mosaic[$j + 1] == '.') {
            $value = '^';
        }else if ($mosaic[$j - 1] == '.' && $mosaic[$j] == '.' && $mosaic[$j + 1] == '^') {
            $value = '^';
        }else{
            $secures++;
            $value = '.';
        }
        $newMosaic[$j] = $value;
    }
    $mosaics[$i] = $newMosaic;
}

echo 'Solution : '.$secures. PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;

