<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$points = [];

foreach ($data as $row) {
    $aux = explode('> ', $row);
    $position = str_replace('position=<','',$aux[0]);
    $velocity = str_replace(['velocity=<','>'],'',$aux[1]);
    [$posX, $posY] = explode(', ',$position);
    [$velX, $velY] = explode(', ',$velocity);
    $points[] = [
        'x' => intval(trim($posX)),
        'y' => intval(trim($posY)),
        'velX' => intval(trim($velX)),
        'velY' => intval(trim($velY)),
    ];
}

$iterations = 0;
while ($iterations < 11000){
    $positions = [];
    $grid = [];
    $minX = PHP_INT_MAX;
    $maxX = -PHP_INT_MAX;
    $minY = PHP_INT_MAX;
    $maxY = -PHP_INT_MAX;
    foreach ($points as $point) {
        $positionX = $point['x']+$iterations*$point['velX'];
        $positionY = $point['y']+$iterations*$point['velY'];
        $positions[$positionY.'-'.$positionX] = true;
        if($positionX < $minX){
            $minX = $positionX;
        }
        if($positionY < $minY){
            $minY = $positionY;
        }
        if($positionX > $maxX){
            $maxX = $positionX;
        }
        if($positionY > $maxY){
            $maxY = $positionY;
        }
    }

    if(abs($minX -  $maxX) < 250 && abs($minY -  $maxY) < 11) {
        for ($row = $minY; $row <= $maxY; $row++) {
            for ($col = $minX; $col <= $maxX; $col++) {
                if (isset($positions[$row.'-'.$col])) {
                    echo '#';
                } else {
                    echo '.';
                }
            }
            echo PHP_EOL;
        }
    }
    $iterations++;
}


echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;
