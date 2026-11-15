<?php

$start = microtime(true);

require_once('../../functions.php');

//$data = loadFile('input.txt');
$data = loadFile('test.txt');

$solution = 0;

$grid = [];

$paths = [];
$straights = [];
$origin = [
    'col' => 0,
    'row' => 0
];

foreach ($data as $line) {
    $paths[] = explode(',', $line);
}

foreach ($paths as $key => $path) {
    $pos = $origin;
    
    foreach ($path as $movement) {
        $direction = substr($movement, 0, 1);
        $steps = intval(substr($movement, 1));
        $colDirection = 0;
        $rowDirection = 0;
        switch ($direction) {
            case 'U':
                $rowDirection = 1;
                break;
            case 'D':
                $rowDirection = -1;
                break;
            case 'R':
                $colDirection = 1;
                break;
            case 'L':
                $colDirection = -1;
                break;
        }
        
        for($i = 1; $i <= $steps; $i++) {
            $pos = [
                'col' => $pos['col'] + $colDirection,
                'row' => $pos['row'] + $rowDirection
            ];
            if(isset($grid[$pos['col']][$pos['row']]) && $grid[$pos['col']][$pos['row']] != $key) {
                $straights[] = $pos;
            }
            $grid[$pos['col']][$pos['row']] = $key;
        }
    }
}

$min = PHP_INT_MAX;
foreach ($straights as $straight) {
    $distance = calculateDistance($origin, $straight);
    if($distance < $min) {
        $min = $distance;
    }
}

$solution = $min;

echo 'Solution : '.$solution.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start, 2).PHP_EOL;
echo 'Memory used: '.number_format(memory_get_peak_usage() / 1024 / 1024, 2).' MB'.PHP_EOL;
echo 'Memory reserved: '.number_format(memory_get_peak_usage(true) / 1024 / 1024, 2).' MB'.PHP_EOL;

function calculateDistance($point1, $point2) {
    $distance = 0;
    foreach (array_keys($point1) as $key) {
        $distance += abs($point1[$key] - $point2[$key]);
    }
    return $distance;
}