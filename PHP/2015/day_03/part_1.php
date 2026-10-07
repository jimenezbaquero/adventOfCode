<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$positionX = 0;
$positionY = 0;
$houses['0-0'] = 1;

foreach (str_split($data[0]) as $direction) {
    switch ($direction) {
        case '^':
            $positionY++;
            break;
        case '>':
            $positionX++;
            break;
        case 'v':
            $positionY--;
            break;
        case '<':
            $positionX--;
            break;
    }
    $house = $positionX.'-'.$positionY;
    $houses[$house] = isset($houses[$house])? $houses[$house] + 1 : 1;
}

echo 'Solution: ' . count($houses).PHP_EOL;
echo 'Tiempo: '.number_format(microtime(true) - $start,2);

