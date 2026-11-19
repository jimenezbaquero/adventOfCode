<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$pixels = $data[0];
$solution = 0;

$wide = 25;
$tall = 6;

$min0 = PHP_INT_MAX;
$minLayer = null;
while($pixels != '') {
    $auxLayer = substr($pixels, 0, $wide * $tall);
    $pixels = substr($pixels, $wide * $tall);
    $num0 = substr_count($auxLayer, '0');
    if ($num0 < $min0) {
        $min0 = $num0;
        $minLayer = $auxLayer;
    }
}

$num1 = substr_count($minLayer, '1');
$num2 = substr_count($minLayer, '2');
$solution = $num1 * $num2;

echo 'Solution : '.$solution.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start, 2).PHP_EOL;
echo 'Memory used: '.number_format(memory_get_peak_usage() / 1024 / 1024, 2).' MB'.PHP_EOL;
echo 'Memory reserved: '.number_format(memory_get_peak_usage(true) / 1024 / 1024, 2).' MB'.PHP_EOL;
