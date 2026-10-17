<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$map = [];
$nodes = [];
$paths = [];

$solution = 'No solution';

foreach ($data as $row => $line) {
    foreach (str_split($line) as $column => $value) {
        $map[$row][$column] = $value;
        if (is_numeric($value)) {
            $nodes[$value] = [
                'row' => $row,
                'column' => $column,
            ];
        }
    }
}


foreach ($nodes as $key1 => $node1) {
    foreach ($nodes as $key2 => $node2) {
        if($key1 <= $key2) {
            continue;
        }
        $paths[$key1.'-'.$key2] = calculatePath($node1, $node2, $map);
    }
}

$solution = calculateMinPath($paths,count($nodes));

echo 'Solution : '.$solution.PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;


function calculatePath($node1, $node2, $map) {
    $solution = 0;
    $queue = [
        [
            'cell' => [
                'row' => $node1['row'],
                'column' => $node1['column']
            ],
            'steps' => 0
        ]
    ];
    
    $visited = [];
    while (!empty($queue)) {
        
        $current = array_shift($queue);
        
        if ($node2['row'].','.$node2['column'] == $current['cell']['row'].','.$current['cell']['column']) {
            $solution = $current['steps'];
            break;
        }
        
        $stateKey = $current['cell']['row'].','.$current['cell']['column'];
        
        if(isset($visited[$stateKey])) {
            continue;
        }
        
        $visited[$stateKey] = true;
        
        $possibilities = calculatePossibilities($current['cell'], $map);
        
        foreach ($possibilities as $possibility) {
            $queue[] = [
                'cell' => $possibility,
                'steps' => $current['steps'] + 1
            ];
        }
    }
    return $solution;
}

function calculatePossibilities($cell, $map): array {
    
    $aux = [];
    $directions = [-1, 0, 1];
    
    foreach ($directions as $directionRow) {
        foreach ($directions as $directionColumn) {
            
            if (abs($directionColumn) == abs($directionRow)) {
                continue;
            }
            
            $newCellRow = (intval($cell['row']) + $directionRow);
            $newCellColumn = (intval($cell['column']) + $directionColumn);
            
            if (!isset($map[$newCellRow][$newCellColumn]) || $map[$newCellRow][$newCellColumn]  == '#') {
                continue;
            }
            
            $aux[] = [
                'row' => $newCellRow,
                'column' => $newCellColumn,
            ];
        }
    }
    
    return $aux;
}

function calculateMinPath($paths, $numNodes) {
    $minNode = 0;
    $maxNode = $numNodes - 1;
    $combinations = permutations(range($minNode, $maxNode));
    $combinations = array_filter($combinations, function ($combination) use($minNode) {return $combination[0] == $minNode;});
    
    
    $minSteps = PHP_INT_MAX;
    foreach ($combinations as $combination) {
        $aux = [];
        $actualNode = $minNode;
        $steps = 0;
        foreach ($combination as $node) {
            if ($node == $actualNode) {
                continue;
            }
            $aux2 = $actualNode . '-' . $node;
            if (!isset($paths[$aux2])) {
                $aux2 = $node . '-' . $actualNode;
            }
            $aux[] = $aux2;
            $actualNode = $node;
        }
        
        foreach ($aux as $path) {
            $steps += $paths[$path];
        }
        
        $aux2 = $actualNode . '-' . 0;
        if (!isset($paths[$aux2])) {
            $aux2 = 0 . '-' . $actualNode;
        }
        
        $steps += $paths[$aux2];
        
        if ($steps < $minSteps) {
            $minSteps = $steps;
        }
    }
    return $minSteps;
}


