<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$discs = [];
$numbers = [];

foreach ($data as $line) {
    $line = str_replace(["Disc #", "has ", 'positions; at time=0, it is at position ', ' .'], '', $line);
    $aux = explode(' ', $line);
    $discs[$aux[0]] = [
        'positions' => intval($aux[1]),
        'initial' => intval($aux[2])
    ];
}

$discs[count($discs) + 1] = [
    'positions' => 11,
    'initial' => 0
];

$time = 0;
$step = 1;

foreach ($discs as $key => $disc) {
    while (($disc['initial'] + $time + $key) % $disc['positions'] != 0) {
        $time += $step;
    }
    
    $step = mcm($step, $disc['positions']);
}


echo 'Solution: '.$time.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start,2);

