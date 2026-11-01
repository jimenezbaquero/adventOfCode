<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$solution = 0;
$points = [];
$minSumDistances = PHP_INT_MAX;
$minPoint = [];
$limit = 10000;

foreach ($data as $item) {
    $aux = explode(", ", $item);
    $column = intval($aux[0]);
    $row = intval($aux[1]);
    $points[$column.','.$row] = [
        $column,
        $row
    ];
}

foreach ($points as $point) {
    $sumDistances = calculateSumDistances($point,$points);
    if($sumDistances < $minSumDistances) {
        $minSumDistances = $sumDistances;
        $minPoint = $point;
    }
}

$numColumn = $minPoint[0];
$numRow = $minPoint[1];
$mustContinue = true;
$iteration = 1;
if($minSumDistances < $limit){
    $solution++;
}
while($mustContinue) {
    $mustContinue = false;
    for ($column = $numColumn - $iteration; $column <= $numColumn + $iteration; $column++) {
        foreach ([
                     $numRow - $iteration,
                     $numRow + $iteration
                 ] as $row) {
            $aux = calculateSumDistances([
                $column,
                $row
            ], $points);
            if ($aux <= $limit) {
                $mustContinue = true;
                if ($aux < $limit) {
                    $visitsPoints[] = [
                        $column,
                        $row
                    ];
                    $solution++;
                }
            }
        }
    }
    for ($row = $numRow - $iteration + 1; $row < $numRow + $iteration; $row++) {
        foreach([$numColumn - $iteration, $numColumn  + $iteration] as $column) {
            $aux = calculateSumDistances([$column, $row], $points);
            if($aux <= $limit){
                $mustContinue = true;
                if($aux < $limit){
                    $visitsPoints[] =  [$column, $row];
                    $solution++;
                }
            }
        }
        
    }
    $iteration++;
}

echo 'Solution : ' . $solution . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;


function calculateSumDistances($cell, $points):int{
    $distance = 0;
    foreach ($points as $point) {
        $distance += calculateDistance($cell, $point);
    }
    return $distance;
}
function calculateDistance($point1, $point2):int{
    return abs($point1[0] - $point2[0]) + abs($point1[1] - $point2[1]);
}