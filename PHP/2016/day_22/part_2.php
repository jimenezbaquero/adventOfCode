<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$nodes = [];
$goal = 0;
$emptyKey = null;
$solution = 'No solution';

$paintNodes = [];

foreach ($data as $line) {
    if (!str_starts_with($line, '/dev/grid')) {
        continue;
    }
    
    $line = str_replace(['/dev/grid/node-', 'x', 'y', 'T', '%'], '', $line);
    $line = preg_replace('/\s+/', '-', $line);
    $aux = explode('-', $line);
    
    $nodes[$aux[0].','.$aux[1]] = [
        'x' => intval($aux[0]),
        'y' => intval($aux[1]),
        'size' => intval($aux[2]),
        'used' => intval($aux[3]),
        'available' => intval($aux[4]),
    ];
    
    if ($aux[0] > $goal && $aux[1] == 0) {
        $goal = $aux[0];
    }
    
    if ($aux[3] == 0) {
        $emptyKey = $aux[0].','.$aux[1];
    }
}

cleanBloquedNode($nodes);

$maxUsed = 0;
$minSize = PHP_INT_MAX;

foreach ($nodes as $node) {
    $maxUsed = max($maxUsed, $node['used']);
    $minSize = min($minSize, $node['size']);
}
if($maxUsed > $minSize) {
    echo 'Solution: '.$solution.PHP_EOL;
    die;
}

$goalKey = $goal.',0';

$queue = [
    [
        'emptyNode' => $emptyKey,
        'goalNode' => $goalKey,
        'steps' => 0
    ]
];


$visited = [];
while (!empty($queue)) {
    
    $current = array_shift($queue);
    
    if ('0,0' == $current['goalNode']) {
        $solution = $current['steps'];
        break;
    }
    
    $stateKey = $current['emptyNode'].'-'.$current['goalNode'];
    
    if(isset($visited[$stateKey])) {
        continue;
    }
    
    $visited[$stateKey] = true;
    
    $possibilities = calculatePossibilities($current, $nodes);
    
    foreach ($possibilities as $possibility) {
        $queue[] = $possibility;
    }
}

echo 'Solution : '.$solution.PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;


function calculatePossibilities($state, $nodes): array {
    
    $aux = [];
    $directions = [-1, 0, 1];
    
    foreach ($directions as $directionX) {
        foreach ($directions as $directionY) {
            
            if (abs($directionX) == abs($directionY)) {
                continue;
            }
            
            $auxEmpty = explode(',', $state['emptyNode']);
            
            $newEmptyNodeX = intval($auxEmpty[0]) + $directionX;
            $newEmptyNodeY = intval($auxEmpty[1]) + $directionY;
            
            $newEmptyKey = $newEmptyNodeX.','.$newEmptyNodeY;
            
            if (!isset($nodes[$newEmptyKey])) {
                continue;
            }
            
            $auxNode = [
                'steps' => $state['steps'] + 1,
                'emptyNode' => $newEmptyKey,
                'goalNode' => $state['goalNode'],
            ];
            
            if ($newEmptyKey == $state['goalNode']) {
                $auxNode['goalNode'] = $state['emptyNode'];
            }
            
            $aux[] = $auxNode;
        }
    }
    
    return $aux;
}

function cleanBloquedNode(array &$nodes) {
    $directions = [-1, 0, 1];
    $hasChanges = true;
    while($hasChanges) {
        $hasChanges = false;
        
        $copyNodes = $nodes;
        uasort($copyNodes, function ($a, $b) {
            return $b['used'] <=> $a['used'];
        });
        $emptyNodes = array_filter($copyNodes, function ($node) {
            return $node['used'] == 0;
        });
        $emptyNode = array_shift($emptyNodes);
        $emptyKey = $emptyNode['x'] . ',' . $emptyNode['y'];
        
        foreach ($copyNodes as $key => $node) {
            if (!isset($nodes[$key])) {
                continue;
            }
            $groupNodes = getNodeGroup($nodes, $key);
            
            if (!in_array($emptyKey, $groupNodes)) {
                $hasChanges = true;
                foreach ($groupNodes as $groupNode) {
                    unset($nodes[$groupNode]);
                }
            }
            
            $valid = false;
            foreach ($directions as $directionX) {
                foreach ($directions as $directionY) {
                    if (abs($directionX) == abs($directionY)) {
                        continue;
                    }
                    $auxKey = explode(',', $key);
                    
                    $newX = intval($auxKey[0]) + $directionX;
                    $newY = intval($auxKey[1]) + $directionY;
                    $newKey = $newX . ',' . $newY;
                    
                    if (!isset($nodes[$newKey])) {
                        continue;
                    }
                    
                    if ($nodes[$newKey]['size'] >= $node['used']) {
                        $valid = true;
                        break 2;
                    }
                }
                
            }
            if (!$valid) {
                $hasChanges = true;
                unset($nodes[$key]);
            }
        }
    }
}


function getNodeGroup(array $nodes, string $startKey): array {
    $visited = [];
    $queue = [$startKey];
    while (!empty($queue)) {
        $currentKey = array_shift($queue);
        
        if (isset($visited[$currentKey])) {
            continue;
        }
        
        $visited[$currentKey] = true;
        
        $directions = [
            [-1, 0],
            [1, 0],
            [0, -1],
            [0, 1]
        ];
        
        [$x, $y] = array_map('intval', explode(',', $currentKey));
        
        foreach ($directions as [$dx, $dy]) {
            $newKey = ($x + $dx) . ',' . ($y + $dy);
            
            if (!isset($nodes[$newKey]) || isset($visited[$newKey])) {
                continue;
            }
            
            if ($nodes[$currentKey]['used'] <= $nodes[$newKey]['size']) {
                $queue[] = $newKey;
            }
        }
    }
    
    return array_keys($visited);
}
