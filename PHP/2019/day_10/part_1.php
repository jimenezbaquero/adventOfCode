<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$grid = [];

foreach ($data as $row => $line) {
    foreach (str_split($line) as $col => $cell) {
        $grid[$row][$col] = $cell;
    }
}

$views = [];
$max = 0;
$point = null;

$vectors = calculateVectors($grid);

foreach ($grid as $row => $cols) {
    foreach ($cols as $col => $cell) {
        if($grid[$row][$col] == '#') {
            $view = calculateViews($row, $col, $grid, $vectors);
            if($view > $max) {
                $max = $view;
                $point = "{$col},{$row}";
            }
        }
    }
}

$solution = $max;
echo $point.PHP_EOL;


echo 'Solution : '.$solution.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start, 2).PHP_EOL;
echo 'Memory used: '.number_format(memory_get_peak_usage() / 1024 / 1024, 2).' MB'.PHP_EOL;
echo 'Memory reserved: '.number_format(memory_get_peak_usage(true) / 1024 / 1024, 2).' MB'.PHP_EOL;


function calculateVectors($grid) {
    $sizeRow = count($grid);
    $sizeCol = count($grid[0]);
    
    $vectors = [
        1 => [0 => true],
        -1 => [0 => true],
    ];
    for ($row = -$sizeRow + 1; $row < $sizeRow; $row++) {
        for ($col = -$sizeCol + 1; $col < $sizeCol; $col++) {
            if ($row == 0 && $col == 0) {
                continue;
            }
            if ($row != 0 && $col != 0) {
                $mcd = mcd(abs($row), abs($col));
                $auxRow = $row / $mcd;
                $auxCol = $col / $mcd;
            } else if ($row == 0) {
                $auxRow = 0;
                $auxCol = $col / abs($col);
            } else if ($col == 0) {
                $auxCol = 0;
                $auxRow = $row / abs($row);
            }
            
            if (!isset($vectors[$auxRow][$auxCol])) {
                $vectors[$auxRow][$auxCol] = true;
            }
        }
    }
    return $vectors;
}

function calculateViews($rowView, $colView, $grid, $vectors) {
    $response = 0;
    foreach ($vectors as $vectorCol => $rows) {
        foreach ($rows as $vectorRow => $value) {
            $cont = 1;
            while (isset($grid[$rowView + ($cont * $vectorRow)][$colView + ($cont * $vectorCol)])) {
                if ($grid[$rowView + ($cont * $vectorRow)][$colView + ($cont * $vectorCol)] == '#') {
                    $response++;
                    break;
                }
                $cont++;
            }
        }
    }
    
    return $response;
}