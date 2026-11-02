<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$solution = 0;
$nodes = [];

foreach ($data as $line) {
    $aux = explode(" ", $line);
    $nodes[$aux[1]]['name'] = $aux[1];
    $nodes[$aux[1]]['next'][] = $aux[7];
    $nodes[$aux[7]]['prev'][] = $aux[1];
}

$initialNodes = [];
foreach ($nodes as $node) {
    if(!isset($node['prev'])) {
        $queue[] = $node['name'];
    }
}

sort($queue);
$visits = [];

while(!empty($queue)) {
    calculateNextNodes($queue, $visits, $nodes);
}

$solution = implode("", array_keys($visits));

echo 'Solution : ' . $solution . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;

function calculateNextNodes(&$queue, &$visits, $nodes): void {
    $node = array_shift($queue);
    $complete = false;
    while(!$complete) {
        $complete = true;
        if (isset($nodes[$node]['prev'])){
            foreach ($nodes[$node]['prev'] as $prev) {
                if (!isset($visits[$prev])) {
                    $aux = array_shift($queue);
                    $queue[] = $node;
                    $node = $aux;
                    $complete = false;
                    break;
                }
            }
        }
    }
    $visits[$node] = true;
    if(isset($nodes[$node]['next'])) {
        foreach ($nodes[$node]['next'] as $next) {
            $queue[] = $next;
        }
    }
    $queue = array_unique($queue);
    sort($queue);
}