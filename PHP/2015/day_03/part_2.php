<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$positionSantaX = 0;
$positionSantaY = 0;
$positionRobotX = 0;
$positionRobotY = 0;
$houses['0-0'] = 2;

$santa = true;

foreach (str_split($data[0]) as $direction) {
    if($santa){
        $positionX = $positionSantaX;
        $positionY = $positionSantaY;
    }else{
        $positionX = $positionRobotX;
        $positionY = $positionRobotY;
    }

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

    if($santa){
        $positionSantaX = $positionX;
        $positionSantaY = $positionY;
    }else{
        $positionRobotX = $positionX;
        $positionRobotY = $positionY;
    }

    $santa = !$santa;
}

echo 'Solution: ' . count($houses).PHP_EOL;
echo 'Tiempo: '.number_format(microtime(true) - $start,2);

