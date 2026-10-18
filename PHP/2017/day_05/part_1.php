<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$total = 0;
$states = [];
$position = 0;
$total = 0;

foreach ($data as $line) {
    $states[] = intval($line);
}

while ($position >= 0 && $position < count($states)) {
    $steps = $states[$position];
    $states[$position]++;
    $position += $steps;
    $total++;
}

echo 'Solution : '.$total.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start, 2).PHP_EOL;
echo 'Memory used: '.number_format(memory_get_peak_usage() / 1024 / 1024, 2).' MB'.PHP_EOL;
echo 'Memory reserved: '.number_format(memory_get_peak_usage(true) / 1024 / 1024, 2).' MB'.PHP_EOL;

