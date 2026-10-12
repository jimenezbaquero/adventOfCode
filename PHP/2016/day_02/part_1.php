<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$instructions = [];

foreach ($data as $line) {
    $instructions[] = $line;
}

$movements = [
    'U' => [0, 1],
    'R' => [1, 0],
    'D' => [0, -1],
    'L' => [-1, 0],
];

$position = [
    'x' => 1,
    'y' => 1,
];

$keypad = [
    2 => [1, 2, 3],
    1 => [4, 5, 6],
    0 => [7, 8, 9],
];

$code = '';
$size = count($keypad);

foreach ($instructions as $instruction) {
    foreach (str_split($instruction) as $movement) {
        $position = [
            'x' => min(max($position['x'] + $movements[$movement][0],0),$size - 1),
            'y' => min(max($position['y'] + $movements[$movement][1],0),$size - 1)
        ];
    }
    $code .= $keypad[$position['y']][$position['x']];
}

echo 'Solution: '.$code.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start,2);

