<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$favoriteNumber = intval($data[0]);
$map = [];
$solution = 0;

$queue = new SplQueue();

$initialPosition = [1, 1];
$destiny = [39, 31];


$queue->enqueue([
    'position' => $initialPosition,
    'steps' => 0
]);

$visited = [
    '1-1' => true
];

$solution = null;

while (!$queue->isEmpty()) {
    
    $current = $queue->dequeue();
    
    $row = $current['position'][0];
    $column = $current['position'][1];
    $steps = $current['steps'];
    
    if($current['position'][0] == $destiny[0] && $current['position'][1] == $destiny[1]) {
        $solution = $current['steps'];
        break;
    }
    
    $neighbors = generateNeighbors($current['position'][0], $current['position'][1], $favoriteNumber, $map);
    
    foreach ($neighbors as $neighbor) {
        $key = $neighbor[0] . '-' . $neighbor[1];
        
        if ($map[$key] == '#' || isset($visited[$key])) {
            continue;
        }
        
        $visited[$key] = true;
        
        $queue->enqueue([
            'position' => $neighbor,
            'steps' => $steps + 1
        ]);
    }
}

echo 'Solution: ' . $solution . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2);

function generateNeighbors($row, $column, $favoriteNumber, &$map) {
    $neighbors = [];
    $directions = [[-1, 0], [1, 0], [0, -1], [0, 1]];
    
    foreach ($directions as $direction) {
        $newRow = $row + $direction[0];
        $newColumn = $column + $direction[1];
        
        if ($newRow < 0 || $newColumn < 0) {
            continue;
        }
        
        $key = $newRow . '-' . $newColumn;
        
        if (!isset($map[$key])) {
            $aux = decbin(
                ($newColumn * $newColumn) +
                (3 * $newColumn) +
                (2 * $newColumn * $newRow) +
                $newRow +
                ($newRow * $newRow) +
                $favoriteNumber
            );
            
            $map[$key] = substr_count($aux, '1') % 2 == 0 ? '.' : '#';
        }
        
        $neighbors[] = [$newRow, $newColumn];
    }
    
    return $neighbors;
}