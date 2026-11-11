<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$solution = 0;
$erosions = [];
const TORCH = 0;
const CLIMBING = 1;
const NEITHER = 2;

$depth = intval(str_replace('depth: ', '', $data[0]));
$target = array_map(function($v) {return intval($v);},explode(',',str_replace('target: ','',$data[1])));

for($row = 0; $row <= $target[1]; $row++) {
    for($col = 0; $col <= $target[0]; $col++) {
        if($row == 0 && $col == 0 || $row == $target[1] && $col == $target[0]) {
            $erosion = $depth % 20183;
            $erosions[$col][$row] = $erosion;
            $grid[$col][$row] = 0;
        }else {
            calculateErosion($col, $row, $depth, $erosions);
        }
    }
}

$queue = new SplPriorityQueue();
$queue->setExtractFlags(SplPriorityQueue::EXTR_DATA);

$times = [];

$initialState = [
    'col' => 0,
    'row' => 0,
    'tool' => TORCH,
    'time' => 0
];

$queue->insert($initialState, 0);
$times[0][0][TORCH] = 0;

$iterations = 0;

while (!$queue->isEmpty()) {
    $iterations++;
    
    $current = $queue->extract();
    
    $bestTime = min($times[$current['col']][$current['row']]);
    if($current['time'] > $bestTime + 7 || $current['time'] > $times[$current['col']][$current['row']][$current['tool']]) {
        continue;
    }
    
    if (
        $current['col'] == $target[0] &&
        $current['row'] == $target[1] &&
        $current['tool'] == TORCH
    ) {
        $solution = $current['time'];
        break;
    }
    
    $allowedTools = getAllowedTools(
        getRegionType($current['col'], $current['row'], $depth, $erosions)
    );
    
    foreach ($allowedTools as $tool) {
        if ($tool === $current['tool']) {
            continue;
        }
        
        $newTime = $current['time'] + 7;
        
        if (
            !isset($times[$current['col']][$current['row']][$tool]) ||
            $newTime < $times[$current['col']][$current['row']][$tool]
        ) {
            $bestTime = PHP_INT_MAX;
            if(isset($times[$current['col']][$current['row']])) {
                $bestTime = min($times[$current['col']][$current['row']]);
            }
            
            if($newTime <= $bestTime + 7) {
                $times[$current['col']][$current['row']][$tool] = $newTime;
                
                $state = [
                    'col' => $current['col'],
                    'row' => $current['row'],
                    'tool' => $tool,
                    'time' => $newTime
                ];
                
                $queue->insert($state, -$newTime);
            }
        }
    }
    
    $directions = [
        [0, -1],
        [0, 1],
        [-1, 0],
        [1, 0]
    ];
    
    foreach ($directions as [$dCol, $dRow]) {
        $col = $current['col'] + $dCol;
        $row = $current['row'] + $dRow;
        
        if ($col < 0 || $row < 0) {
            continue;
        }
        
        $allowedTools = getAllowedTools(
            getRegionType($col, $row, $depth, $erosions)
        );
        
        if (!in_array($current['tool'], $allowedTools, true)) {
            continue;
        }
        
        $newTime = $current['time'] + 1;
        
        if (
            !isset($times[$col][$row][$current['tool']]) ||
            $newTime < $times[$col][$row][$current['tool']]
        ) {
            $bestTime = PHP_INT_MAX;
            if(isset($times[$col][$row])) {
                $bestTime = min($times[$col][$row]);
            }
            if($newTime <= $bestTime + 7) {
                $times[$col][$row][$current['tool']] = $newTime;
                
                $state = [
                    'col' => $col,
                    'row' => $row,
                    'tool' => $current['tool'],
                    'time' => $newTime
                ];
                
                $queue->insert($state, -$newTime);
            }
        }
    }
}
echo 'Iteraciones: '.$iterations.PHP_EOL;

echo 'Solution : ' . $solution . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;

function getAllowedTools($type) {
    switch ($type) {
        case 0:
            return [TORCH, CLIMBING];
        case 1:
            return [CLIMBING, NEITHER];
        case 2:
            return [TORCH, NEITHER];
    }
}

function calculateErosion($col, $row, $depth, &$erosions){
    if(isset($erosions[$col][$row])) {
        return $erosions[$col][$row];
    }
    if($row == 0) {
        $index = $col * 16807;
    }else if($col == 0) {
        $index = $row * 48271;
    }else{
        if(!isset($erosions[$col][$row - 1])) {
            calculateErosion($col, $row - 1, $depth, $erosions);
        }
        if(!isset($erosions[$col - 1][$row])) {
            calculateErosion($col - 1, $row, $depth, $erosions);
        }
        $index = $erosions[$col][$row - 1] * $erosions[$col - 1][$row];
    }
    
    $erosion = ($index + $depth) % 20183;
    $erosions[$col][$row] = $erosion;
    return $erosion;
}

function getRegionType($col, $row, $depth, &$erosions) {
    return calculateErosion($col, $row, $depth, $erosions) % 3;
}