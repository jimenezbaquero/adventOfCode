<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$solution = 0;
$initialWater = [
    0,
    500
];

$grid = [];
$maxY = 0;
$minY = PHP_INT_MAX;

foreach ($data as $line) {
    $aux = explode(", ", $line);
    $aux1 = explode("=", $aux[0]);
    $aux2 = explode("=", $aux[1]);
    $aux2 = explode('..', $aux2[1]);
    if ($aux1[0] == "x") {
        $initialX = $aux1[1];
        $finishX = $aux1[1];
        $initialY = $aux2[0];
        $finishY = $aux2[1];
    } else {
        $initialY = $aux1[1];
        $finishY = $aux1[1];
        $initialX = $aux2[0];
        $finishX = $aux2[1];
    }
    for ($i = $initialX; $i <= $finishX; $i++) {
        for ($j = $initialY; $j <= $finishY; $j++) {
            $grid[$j][$i] = '#';
        }
    }
    if($initialY < $minY) {
        $minY = $initialY;
    }
    if ($finishY > $maxY) {
        $maxY = $finishY;
    }
}

$continue = true;

while ($continue) {
    $processed = [];
    $continue = calculateWater($initialWater, $grid, $maxY, $processed);
}


foreach ($grid as $key => $row) {
    if($key < $minY || $key > $maxY) {
        continue;
    }
    foreach ($row as $cell) {
        if($cell == 'W' || $cell == '|') {
            $solution++;
        }
    }
}


echo 'Solution : ' . $solution . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;

function calculateWater($initialWater, &$grid, $maxY, &$processed) {
    $processed[implode(',', $initialWater)] = true;
    $addWater = false;
    [$posY, $posX] = $initialWater;
    if(isset($grid[$posY][$posX]) && $grid[$posY][$posX] == 'W') {
        return false;
    }
    $findBase = false;
    while ($posY < $maxY) {
        $posY++;
        if (isset($grid[$posY][$posX]) && ($grid[$posY][$posX] == '#' || $grid[$posY][$posX] == 'W')) {
            $posY--;
            $findBase = true;
            break;
        }
        $grid[$posY][$posX] = '|';
    }
    if (!$findBase) {
        return;
    }
    
    $rebase = false;
    while (!$rebase && $posY >= 0) {
        $aux = $posX;
        while (!isset($grid[$posY][$aux]) || ($grid[$posY][$aux] != '#' )) {
            if (!isset($grid[$posY + 1][$aux]) || $grid[$posY + 1][$aux] == '|') {
                $rebase = true;
                break;
            }
            $aux--;
        }
        $aux = $posX;
        while (!isset($grid[$posY][$aux]) || ($grid[$posY][$aux] != '#' )) {
            if (!isset($grid[$posY + 1][$aux]) || $grid[$posY + 1][$aux] == '|') {
                $rebase = true;
                break;
            }
            $aux++;
        }
        
        if ($rebase) {
            $aux = $posX;
            while (isset($grid[$posY + 1][$aux]) && ($grid[$posY + 1][$aux] == 'W' || $grid[$posY + 1][$aux] == '#') && (!isset($grid[$posY][$aux]) || $grid[$posY][$aux] != '#')) {
                $grid[$posY][$aux] = '|';
                $aux--;
                if ((!isset($grid[$posY][$aux]) || $grid[$posY][$aux] != '#') &&
                    (!isset($grid[$posY + 1][$aux]) || $grid[$posY + 1][$aux] == '|')) {
                    $grid[$posY][$aux] = '|';
                    if (!isset($processed[$posY.','.$aux])) {
                        $addWater = calculateWater([
                                $posY,
                                $aux
                            ], $grid, $maxY, $processed) || $addWater;
                    }
                    break;
                }
            }
            $aux = $posX;
            while (isset($grid[$posY + 1][$aux]) && ($grid[$posY + 1][$aux] == 'W' || $grid[$posY + 1][$aux] == '#') && (!isset($grid[$posY][$aux]) || $grid[$posY][$aux] != '#')) {
                $grid[$posY][$aux] = '|';
                $aux++;
                if ((!isset($grid[$posY][$aux]) || $grid[$posY][$aux] != '#') &&
                    (!isset($grid[$posY + 1][$aux]) || $grid[$posY + 1][$aux] == '|')) {
                    $grid[$posY][$aux] = '|';
                    if (!isset($processed[$posY.','.$aux])) {
                        $addWater = calculateWater([
                                $posY,
                                $aux
                            ], $grid, $maxY, $processed) || $addWater;
                    }
                    break;
                }
            }
        } else {
            $aux = $posX;
            while (isset($grid[$posY + 1][$aux]) && ($grid[$posY + 1][$aux] == 'W' || $grid[$posY + 1][$aux] == '#') && (!isset($grid[$posY][$aux]) || $grid[$posY][$aux] != '#')) {
                if (!isset($grid[$posY][$aux]) || $grid[$posY][$aux] != 'W') {
                    $grid[$posY][$aux] = 'W';
                    $addWater = true;
                }
                $aux--;
            }
            $aux = $posX;
            while (isset($grid[$posY + 1][$aux]) && ($grid[$posY + 1][$aux] == 'W' || $grid[$posY + 1][$aux] == '#') && (!isset($grid[$posY][$aux]) || $grid[$posY][$aux] != '#')) {
                if (!isset($grid[$posY][$aux]) || $grid[$posY][$aux] != 'W') {
                    $grid[$posY][$aux] = 'W';
                    $addWater = true;
                }
                $aux++;
            }
            $posY--;
        }
    }
    
    return $addWater;
}