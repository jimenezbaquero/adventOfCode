<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$values = explode(' ',$data[0]);

$solution = 0;
$nodes = [];
$parentNode = null;
$position = 0;
$numValues = count($values);
while($position < $numValues) {
    $position = calculateNodes($values, $position, $parentNode, $nodes);
}

foreach ($nodes as $node) {
    $solution += array_sum($node['metadata']);
}

echo 'Solution : ' . $solution . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;

function calculateNodes($values, $position, $parentNode, &$nodes): int{
    $node = intval($position);
    $numChild = intval($values[$position]);
    $numMetadata = intval($values[$position + 1]);
    $nodes[$node] = [
        'name' => $node,
        'parent' => $parentNode,
        'numChild' => $numChild,
        'numMetadata' => $numMetadata,
        'metadata' => []
    ];
    $position = $position + 2;
    if($numChild > 0){
        for($i = 0; $i < $numChild; $i++){
            $position = calculateNodes($values, $position, $node, $nodes);
        }
    }
    
    for($j = 0; $j < $numMetadata; $j++){
        $nodes[$node]['metadata'][] = $values[$position++];
    }
    
    return $position;
}