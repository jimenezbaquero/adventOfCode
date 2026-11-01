<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$solution = 0;
$points = [];
$minColumn = PHP_INT_MAX;
$minRow = PHP_INT_MAX;
$maxColumn = 0;
$maxRow = 0;


foreach ($data as $item) {
    $aux = explode(", ", $item);
    $column = intval($aux[0]);
    $row = intval($aux[1]);
    $points[$column.','.$row] = [
        $column,
        $row
    ];
    if($column < $minColumn) {
        $minColumn = $column;
    }
    if($column > $maxColumn) {
        $maxColumn = $column;
    }
    if($row < $minRow) {
        $minRow = $row;
    }
    if($row > $maxRow) {
        $maxRow = $row;
    }
}

$infinitePoints = [];
$rectanglePoints = [];
for ($column = $minColumn; $column <= $maxColumn; $column++) {
    $row = $minRow;
    $aux = findStraightPoint([$column, $row], $points);
    if(!empty($aux)) {
        $infinitePoints[$aux[0] . ',' . $aux[1]] = $aux;
    }
}

for ($column = $minColumn; $column <= $maxColumn; $column++) {
    $row = $maxRow;
    $aux = findStraightPoint([$column, $row], $points);
    if(!empty($aux)) {
        $infinitePoints[$aux[0] . ',' . $aux[1]] = $aux;
    }
}

for ($row = $minRow + 1; $row < $maxRow; $row++) {
    $column = $minColumn;
    $aux = findStraightPoint([$column, $row], $points);
    if(!empty($aux)) {
        $infinitePoints[$aux[0] . ',' . $aux[1]] = $aux;
    }
}

for ($row = $minRow + 1; $row < $maxRow; $row++) {
    $column = $maxColumn;
    $aux = findStraightPoint([$column, $row], $points);
    if(!empty($aux)) {
        $infinitePoints[$aux[0] . ',' . $aux[1]] = $aux;
    }
}

$areas = [];
$maxArea = 0;
for ($column = $minColumn + 1; $column < $maxColumn; $column++) {
    for($row = $minRow + 1; $row < $maxRow; $row++) {
        $aux = findStraightPoint([$column, $row], $points);
        if(!empty($aux)) {
            if(!isset($infinitePoints[$aux[0] . ',' . $aux[1]])) {
                if (!isset($areas[$aux[0] . ',' . $aux[1]])) {
                    $areas[$aux[0] . ',' . $aux[1]] = 0;
                }
                $areas[$aux[0] . ',' . $aux[1]]++;
                
                if ($areas[$aux[0] . ',' . $aux[1]] > $maxArea) {
                    $maxArea = $areas[$aux[0] . ',' . $aux[1]];
                }
            }
        }
    }
}

$solution = $maxArea;

echo 'Solution : ' . $solution . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;

function findStraightPoint($pointRectangle, $points):array {
    $minDistance = PHP_INT_MAX;
    $minPoint = [];
    foreach ($points as $point) {
        $aux = calculateDistance($pointRectangle, $point);
        if($aux < $minDistance) {
            $minDistance = $aux;
            $minPoint = $point;
        }else if($aux == $minDistance) {
            $minPoint = [];
        }
    }
    return $minPoint;
}

function calculateDistance($point1, $point2):int{
    return abs($point1[0] - $point2[0]) + abs($point1[1] - $point2[1]);
}