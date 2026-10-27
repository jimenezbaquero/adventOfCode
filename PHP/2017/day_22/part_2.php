<?php
$start = microtime(true);
require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$grid = [];
$initialGrid = [];
$solution = 0;

foreach ($data as $row => $line) {
    foreach (str_split($line) as $col => $char) {
        $initialGrid[$row][$col] = $char;
    }
}

$grid = $initialGrid;
$position = [intdiv(count($grid),2), intdiv(count($grid),2)];
$direction = 'up';

$iteration = 0;
$directions = [
    'up' => [-1, 0],
    'right' => [0, 1],
    'down' => [1, 0],
    'left' => [0, -1]
];

while ($iteration < 10000000) {
    $iteration++;
    if(isset($grid[$position[0]][$position[1]]) && $grid[$position[0]][$position[1]] == '#') {
        switch ($direction) {
            case 'up':
                $direction = 'right';
                break;
            case 'right':
                $direction = 'down';
                break;
            case 'down':
                $direction = 'left';
                break;
            case 'left':
                $direction = 'up';
                break;
        }
        $grid[$position[0]][$position[1]] = 'F';
    }else if(isset($grid[$position[0]][$position[1]]) && $grid[$position[0]][$position[1]] == 'W') {
        $grid[$position[0]][$position[1]] = '#';
        $solution++;
    }else if(isset($grid[$position[0]][$position[1]]) && $grid[$position[0]][$position[1]] == 'F') {
        switch ($direction) {
            case 'up':
                $direction = 'down';
                break;
            case 'right':
                $direction = 'left';
                break;
            case 'down':
                $direction = 'up';
                break;
            case 'left':
                $direction = 'right';
                break;
        }
        $grid[$position[0]][$position[1]] = '.';
    }else{
        switch ($direction) {
            case 'up':
                $direction = 'left';
                break;
            case 'right':
                $direction = 'up';
                break;
            case 'down':
                $direction = 'right';
                break;
            case 'left':
                $direction = 'down';
                break;
        }
        $grid[$position[0]][$position[1]] = 'W';
    }

    $position = [$position[0] + $directions[$direction][0], $position[1] + $directions[$direction][1]];
}

echo 'Solution: '.$solution.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start,2).PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;
