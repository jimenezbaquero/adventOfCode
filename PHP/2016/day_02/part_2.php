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
    'x' => 0,
    'y' => 2,
];

$keypad = [
    4 => [null, null, 1, null, null],
    3 => [null, 2, 3, 4, null],
    2 => [5, 6, 7, 8, 9],
    1 => [null, 'A', 'B', 'C', null],
    0 => [null, null, 'D', null, null],
];

$code = '';
$size = count($keypad);

foreach ($instructions as $instruction) {
    foreach (str_split($instruction) as $movement) {
        $newX = $position['x'] + $movements[$movement][0];
        $newY = $position['y'] + $movements[$movement][1];
        if(isset($keypad[$newY][$newX]) && !is_null($keypad[$newY][$newX])) {
            $position['x'] = $newX;
            $position['y'] = $newY;
        }
    }
    $code .= $keypad[$position['y']][$position['x']];
}

echo 'Solution: '.$code.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start,2);

