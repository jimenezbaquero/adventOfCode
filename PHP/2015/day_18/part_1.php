<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$grid = [];
$iterations = 100;
$numberLights = 0;

foreach ($data as $line) {
    $grid[] = str_split($line);
}

for ($i = 0; $i < $iterations; $i++) {
    $newGrid = [];
    foreach ($grid as $x => $row) {
        foreach ($row as $y => $cell) {
            $newState = newState($grid, $x, $y);
            $newGrid[$x][$y] = $newState;
            if($i == ($iterations - 1) &&$newState == '#'){
                $numberLights++;
            }
        }
    }
    $grid = $newGrid;
}

echo 'Solution: ' .$numberLights.  PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2);

function newState($grid, $x, $y) {
    $directions = [-1, 0, 1];
    
    $numberLights = 0;
    foreach ($directions as $directionX) {
        foreach ($directions as $directionY) {
            if ($directionX == 0 && $directionY == 0) {
                continue;
            }
            $newX = $x + $directionX;
            $newY = $y + $directionY;
            
            if (!isset($grid[$newX][$newY])) {
                continue;
            }
            if ($grid[$newX][$newY] == '#') {
                $numberLights++ ;
            }
        }
    }
    
    if($grid[$x][$y] == '#') {
        if($numberLights == 2 || $numberLights == 3) {
            return '#';
        }
    }else{
        if($numberLights == 3) {
            return '#';
        }
    }
    
    return '.';
    
}