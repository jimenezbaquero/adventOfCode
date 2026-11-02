<?php

$start = microtime(true);

require_once('../../functions.php');

$marbles = 7143100;
$numPlayers = 476;

//$marbles = 1618;
//$numPlayers = 10;

$solution = 0;

$state = [];
$players = [];

$nexts = [
    0 => 2,
    1 => 0,
    2 => 1
];

$prevs = [
    0 => 1,
    1 => 2,
    2 => 0
];

$actual = 2;

for ($i = 3; $i <= $marbles; $i++) {
    $player = $i % $numPlayers;
    if($i % 23 != 0) {
        $prev = $nexts[$actual];
        $next = $nexts[$prev];
        $nexts[$i] = $next;
        $prevs[$i] = $prev;
        $nexts[$prev] = $i;
        $prevs[$next] = $i;
        $actual = $i;
    }else{
        for ($t = 0; $t < 7; $t++) {
            $actual = $prevs[$actual];
        }
        $points = $i + $actual;
        if(!isset($players[$player])){
            $players[$player] = 0;
        }
        $players[$player]+= $points;
        $prev = $prevs[$actual];
        $next = $nexts[$actual];
        $nexts[$prev] = $next;
        $prevs[$next] = $prev;
        unset($nexts[$actual], $prevs[$actual]);
        $actual = $next;
    }
}

$solution = max($players);

echo 'Solution : ' . $solution . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;
