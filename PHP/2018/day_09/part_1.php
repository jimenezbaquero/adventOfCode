<?php

$start = microtime(true);

require_once('../../functions.php');

$marbles = 71431;
$numPlayers = 476;

//$marbles = 1618;
//$numPlayers = 10;

$solution = 0;

$state = [];
$players = [];

$state[0] = [
    'next' => 2,
    'prev' => 1,
];

$state[1] = [
    'next' => 0,
    'prev' => 2,
];

$state[2] = [
    'next' => 1,
    'prev' => 0,
];

$actual = 2;

for ($i = 3; $i <= $marbles; $i++) {
    $player = $i % $numPlayers;
    if($i % 23 != 0) {
        $prev = $state[$actual]['next'];
        $next = $state[$prev]['next'];
        $state[$i] = [
            'prev' => $prev,
            'next' => $next,
        ];
        $state[$prev]['next'] = $i;
        $state[$next]['prev'] = $i;
        $actual = $i;
    }else{
        for ($t = 0; $t < 7; $t++) {
            $actual = $state[$actual]['prev'];
        }
        $points = $i + $actual;
        if(!isset($players[$player])){
            $players[$player] = 0;
        }
        $players[$player]+= $points;
        $prev = $state[$actual]['prev'];
        $next = $state[$actual]['next'];
        $state[$prev]['next'] = $next;
        $state[$next]['prev'] = $prev;
        unset ($state[$actual]);
        $actual = $next;
    }
}

$solution = max($players);

echo 'Solution : ' . $solution . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;
