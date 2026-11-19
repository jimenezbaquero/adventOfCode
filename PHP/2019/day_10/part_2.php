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

$max = 0;
$point = null;

$vectors = calculateVectors($grid);

foreach ($grid as $row => $cols) {
    foreach ($cols as $col => $cell) {
        if ($cell == '#') {
            $view = calculateViews($row, $col, $grid, $vectors);
            
            if ($view > $max) {
                $max = $view;
                $point = ['row' => $row, 'col' => $col];
            }
        }
    }
}

$orderedVectors = [];

foreach ($vectors as $row => $cols) {
    foreach ($cols as $col => $value) {
        $orderedVectors[] = [
            'row' => $row,
            'col' => $col
        ];
    }
}

usort($orderedVectors, function ($a, $b) {
    $angleA = atan2($a['col'], -$a['row']);
    $angleB = atan2($b['col'], -$b['row']);
    
    $angleA = $angleA < 0 ? $angleA + 2 * M_PI : $angleA;
    $angleB = $angleB < 0 ? $angleB + 2 * M_PI : $angleB;
    
    return $angleA <=> $angleB;
});

$point200 = eliminatePoints($orderedVectors, $point, $grid, 200);

if($point200 != false) {
    $solution = $point200['col'] * 100 + $point200['row'];
}else{
    $solution = 'Sin solución';
}

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
            } else {
                $auxRow = $row / abs($row);
                $auxCol = 0;
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
    
    foreach ($vectors as $vectorRow => $cols) {
        foreach ($cols as $vectorCol => $value) {
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


function eliminatePoints($vectors, $point, $grid, $max) {
    $counter = 0;
    $eliminatePoint = null;
    
    while ($counter < $max) {
        if(empty($vectors)) {
            return false;
        }
        $vector = array_shift($vectors);
        $i = 1;
        
        while (isset($grid[$point['row'] + $vector['row'] * $i][$point['col'] + $vector['col'] * $i])) {
            if ($grid[$point['row'] + $vector['row'] * $i][$point['col'] + $vector['col'] * $i] == '#') {
                $grid[$point['row'] + $vector['row'] * $i][$point['col'] + $vector['col'] * $i] = '.';
                
                $eliminatePoint = [
                    'row' => $point['row'] + $vector['row'] * $i,
                    'col' => $point['col'] + $vector['col'] * $i
                ];
                
                $counter++;
                array_push($vectors, $vector);
                break;
            }
            
            $i++;
        }
        
        
    }
    
    return $eliminatePoint;
}
