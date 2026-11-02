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
    $position = calculateNodes($values, $position, $parentNode, 0,$nodes);
}


$solution = calculateSum(0,$nodes);


echo 'Solution : ' . $solution . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;

function calculateNodes($values, $position, $parentNode, $reference, &$nodes): int{
    $node = intval($position);
    $numChild = intval($values[$position]);
    $numMetadata = intval($values[$position + 1]);
    $nodes[$node] = [
        'name' => $node,
        'parent' => $parentNode,
        'reference' => $reference,
        'numChild' => $numChild,
        'numMetadata' => $numMetadata,
        'metadata' => []
    ];
    $position = $position + 2;
    if($numChild > 0){
        for($i = 0; $i < $numChild; $i++){
            $position = calculateNodes($values, $position, $node, $i + 1, $nodes);
        }
    }
    
    for($j = 0; $j < $numMetadata; $j++){
        $nodes[$node]['metadata'][] = $values[$position++];
    }
    
    return $position;
}

function calculateSum($node, $nodes){
    if ($nodes[$node]['numChild'] == 0) {
        return array_sum($nodes[$node]['metadata']);
    }
    $sum = 0;
    foreach($nodes[$node]['metadata'] as $reference){
        $childNode = findNode($node, $reference , $nodes);
        if(!is_null($childNode)){
            $sum += calculateSum($childNode, $nodes);
        }
    }
    return $sum;
}

function findNode($parent, $reference, $nodes){
    foreach($nodes as $node){
        if($node['parent'] == $parent && $node['reference'] == $reference){
            return $node['name'];
        }
    }
    return null;
}