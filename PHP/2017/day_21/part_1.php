<?php
$start = microtime(true);
require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$rules = [];

foreach ($data as $line) {
    $aux = explode(' => ',$line);
    $options = calculateOptions($aux[0]);
    foreach ($options as $option) {
        $rules[$option] = $aux[1];
    }
}

$grid = '.#./..#/###';

$iteration = 0;

while ($iteration < 5) {
    $iteration++;
    $squares = [];
    $aux = explode('/', $grid);
    $parts = 0;
    if(strlen($aux[0]) % 2 == 0) {
        $parts = strlen($aux[0]) / 2;
        for ($i=0; $i < $parts; $i++) {
            for ($j=0; $j < $parts; $j++) {
                $squares[$i.'-'.$j] = $aux[2*$i][2*$j].$aux[2*$i][2*$j+1].'/'.$aux[2*$i+1][2*$j].$aux[2*$i+1][2*$j+1];
            }
        }
    }else if(strlen($aux[0]) % 3 == 0) {
        $parts = strlen($aux[0]) / 3;
        for ($i=0; $i < $parts; $i++) {
            for ($j=0; $j < $parts; $j++) {
                $squares[$i.'-'.$j] = $aux[3*$i][3*$j].$aux[3*$i][3*$j+1].$aux[3*$i][3*$j+2].'/'.
                    $aux[3*$i+1][3*$j].$aux[3*$i+1][3*$j+1].$aux[3*$i+1][3*$j+2].'/'.
                    $aux[3*$i+2][3*$j].$aux[3*$i+2][3*$j+1].$aux[3*$i+2][3*$j+2];
            }
        }
    }

    $transforms = [];
    foreach ($squares as $key => $square) {
        $transforms[$key] = $rules[$square];
    }

    ksort($transforms);
    $rows = [];
    $size = 0;
    $nPartRow = 0;
    for($i=0; $i < $parts; $i++) {
        for($j=0; $j < $parts; $j++) {
            $aux = explode('/', $transforms[$i.'-'.$j]);
            $size = count($aux);
            for($k=0; $k < $size ; $k++) {
                if(!isset($rows[$nPartRow + $k])) {
                    $rows[$nPartRow + $k] = '';
                }
                $rows[$nPartRow + $k] .= $aux[$k];
            }
        }
        $nPartRow += $size;
    }


    $grid = implode('/', $rows);
}

$solution = substr_count($grid, '#');

echo 'Solution: '.$solution.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start,2).PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;

function calculateOptions($input) {
    $matrix = [];
    $options = [];
    $aux = explode('/', $input);
    foreach ($aux as $row => $line) {
        foreach(str_split($line) as $col => $char) {
            $matrix[$row][$col] = $char;
        };
    }
    $matrixs[] = $matrix;
    $auxMatrix = $matrix;
    for ($i=0; $i < 3; $i++) {
        $auxMatrix = rotate($auxMatrix);
        $matrixs[] = $auxMatrix;
    }
    $rotations = $matrixs;
    foreach ($rotations as $matrix) {
        $matrixs[] = flip($matrix);
    }

    foreach ($matrixs as $matrix) {
        $options[] = matrixToString($matrix);
    }
    return array_unique($options);
}
