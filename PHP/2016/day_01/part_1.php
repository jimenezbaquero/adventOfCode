<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$instructions = explode(', ',$data[0]);

$movements = [
    'N' => [
        'R' => [1,0,'E'],
        'L' => [-1, 0, 'W']
    ],
    'E' => [
        'R' => [0,-1, 'S'],
        'L' => [0, 1, 'N']
    ],
    'S' => [
        'R' => [-1,0, 'W'],
        'L' => [1, 0, 'E']
    ],
    'W' => [
        'R' => [0, 1, 'N'],
        'L' => [0, -1, 'S']
    ],
];

$position = [
    'x' => 0,
    'y' => 0,
    'direction' => 'N'
];

foreach ($instructions as $instruction) {
    $instructionDirection = $instruction[0];
    $amount = intval(substr($instruction, 1));
    $position = [
        'x' => $position['x'] + $amount * $movements[$position['direction']][$instructionDirection][0],
        'y' => $position['y'] + $amount * $movements[$position['direction']][$instructionDirection][1],
        'direction' => $movements[$position['direction']][$instructionDirection][2]
    ];
}

//distancia taxi
$solution = abs($position['x']) +abs($position['y']);

echo 'Solution: '.$solution.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start,2);

