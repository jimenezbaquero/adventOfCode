<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$solution = 0;

$polymer = $data[0];
$hasNext = true;
$position = 0;

while ($hasNext) {
    $char = $polymer[$position];
    $next = $polymer[$position + 1];
    if($char == strtoupper($next) || $char == strtolower($next)) {
        $polymer = substr($polymer, 0, $position).substr($polymer, $position + 2);
        $position = max(0, $position - 1);
    }else{
        $position++;
    }
    if(strlen($polymer) < $position + 2) {
        $hasNext = false;
    }
}

$solution = $polymer;

echo 'Solution : ' . $solution . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;

