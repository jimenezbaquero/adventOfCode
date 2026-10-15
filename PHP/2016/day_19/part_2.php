<?php

$start = microtime(true);

require_once('../../functions.php');

$elves = 3004953;

$n = 0;
while(3 ** ($n + 1) <= $elves){
    $n++;
}

$value = 3 ** $n;

if($elves == $value){
    $solution = $value;
}else if($elves <= 2 * $value){
    $solution = $elves % $value;
}else{
    $solution = 2 * ($elves % $value) + $value;
}

echo 'Solution : '.$solution. PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;

