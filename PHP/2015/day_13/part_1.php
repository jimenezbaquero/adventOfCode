<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$instructions = [];
$names = [];

foreach ($data as $line) {
    $aux = explode(" ", str_replace('.','',$line));
    $instructions[$aux[0]][$aux[10]] = $aux[2] == 'gain' ? $aux[3] : $aux[3] * (-1);
    $names[] = $aux[0];
}

$names = array_unique($names);

$options = circularPermutations($names);
$maxHappiness = 0;

foreach ($options as $option) {
    $happiness = calculateHappiness($option, $instructions);
    if($maxHappiness < $happiness) {
        $maxHappiness = $happiness;
    }
}

echo 'Solution: ' . $maxHappiness . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2);


function calculateHappiness($option, $instructions) {
    $happiness = 0;
    $size = count($option);
    foreach ($option as $key => $person) {
        $next = ($key + 1) % $size;
        $prev = ($key + $size -1) % $size;
        $happiness += $instructions[$person][$option[$prev]];
        $happiness += $instructions[$person][$option[$next]];
    }
    return $happiness;
}