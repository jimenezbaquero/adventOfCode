<?php

$start = microtime(true);

require_once('../../functions.php');

$generatorA = 679;
$generatorB = 771;

//$generatorA = 65;
//$generatorB = 8921;

$factorA = 16807;
$factorB = 48271;
$divisor = 2147483647;

$total = 0;

$valueA = $generatorA;
$valueB = $generatorB;

for($i = 0; $i < 40000000; $i++) {
    $valueA = ($valueA * $factorA) % $divisor;
    $valueB = ($valueB * $factorB) % $divisor;
    if (($valueA & 0xFFFF) == ($valueB & 0xFFFF)) {
        $total++;
    }
}


echo 'Solution : ' . $total . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;

