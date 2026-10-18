<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$total = 0;
$steps = 0;

$states = explode("\t", $data[0]);
$configurations = [];

$numStates = count($states);

while (true) {
    $steps++;
    [$position, $max] = findMax($states);
    $states[$position] = 0;
    for ($i = 1; $i <= $max; $i++) {
        $states[($position + $i) % $numStates]++;
    }
    $configuration = implode(",", $states);
    if (isset($configurations[$configuration])) {
        $total = $steps - $configurations[$configuration];
        break;
    }else{
        $configurations[$configuration] = $steps;
    }
}

echo 'Solution : '.$total.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start, 2).PHP_EOL;
echo 'Memory used: '.number_format(memory_get_peak_usage() / 1024 / 1024, 2).' MB'.PHP_EOL;
echo 'Memory reserved: '.number_format(memory_get_peak_usage(true) / 1024 / 1024, 2).' MB'.PHP_EOL;

function findMax($states) {
    $max = 0;
    $position = 0;
    foreach ($states as $key => $state) {
        if($state > $max) {
            $position = $key;
            $max = $state;
        }
    }
    return [$position, $max];
}