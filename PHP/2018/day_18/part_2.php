<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$solution = 0;

$grid = [];
$states = [];

foreach ($data as $row => $line) {
    foreach (str_split($line) as $col => $char) {
        $grid[$row][$col] = $char;
    }
}

$existsState = false;
$states[toStringState($grid)] = 0;
$amounts = [];
$minutes = 0;
$findCycle = false;
while($minutes < 1000000000 && !$findCycle){
    $newGrid = [];
    foreach ($grid as $row => $rowData) {
        foreach ($rowData as $col => $cell) {
            $scan = scanAdjacent($row, $col, $grid);
            switch ($grid[$row][$col]) {
                case '.':
                    if($scan['tree'] >= 3){
                        $newGrid[$row][$col] = '|';
                    }else{
                        $newGrid[$row][$col] = '.';
                    }
                    break;
                case '|':
                    if($scan['lumberyard'] >= 3){
                        $newGrid[$row][$col] = '#';
                    }else{
                        $newGrid[$row][$col] = '|';
                    }
                    break;
                case'#':
                    if($scan['lumberyard'] >= 1 && $scan['tree'] >= 1){
                        $newGrid[$row][$col] = '#';
                    }else{
                        $newGrid[$row][$col] = '.';
                    }
            }
        }
    }
    $grid = $newGrid;
    $minutes++;

    $currentState = toStringState($grid);

    if(isset($states[$currentState])) {
        $existsState = $states[$currentState];
        $findCycle = true;
    }else{
        $states[$currentState] = $minutes;
    }
}

$cycle = $minutes - $existsState;
$finalState = $existsState + (1000000000 - $existsState) % $cycle;
$state = array_search($finalState, $states);

$solution = substr_count($state,'|') * substr_count($state,'#');

echo 'Solution : ' . $solution . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;

function scanAdjacent($row, $col, $grid){
    $movements = [ -1, 0, 1 ];
    $scan = [
        'open' => 0,
        'tree' => 0,
        'lumberyard' => 0
    ];

    foreach ($movements as $movY => $movementY) {
        foreach($movements as $movX => $movementX){
            if(($movementX == 0 && $movementY == 0) || !isset($grid[$row + $movementY][$col + $movementX])){
                continue;
            }

            switch ($grid[$row + $movementY][$col + $movementX]) {
                case '.':
                    $scan['open']++;
                    break;
                case '|':
                    $scan['tree']++;
                    break;
                case '#':
                    $scan['lumberyard']++;
                    break;
            }
        }
    }
    return $scan;
}

function toStringState($grid){
    $aux = '';
    foreach ($grid as $row) {
        $aux .= implode('',$row);
    }
    return $aux;
}

