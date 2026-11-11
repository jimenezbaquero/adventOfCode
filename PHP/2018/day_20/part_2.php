<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$solution = 0;

$expression = str_replace(['^','$'],'',$data[0]);

$grid[0][0] = 'X';

$positions = ['0,0'];
$divergences = [];
$endings = [];

$pos = 0;

while($pos < strlen($expression)) {
    $char = substr($expression, $pos, 1);
    
    switch($char) {
        case '(':
            $divergences[] = $positions;
            $endings[] = [];
            break;
        
        case '|':
            $level = count($endings) - 1;
            $endings[$level] = array_merge($endings[$level], $positions);
            $positions = $divergences[$level];
            break;
        
        case ')':
            $level = count($endings) - 1;
            $endings[$level] = array_merge($endings[$level], $positions);
            $positions = array_values(array_unique($endings[$level]));
            array_pop($divergences);
            array_pop($endings);
            break;
        
        case 'N':
        case 'S':
        case 'E':
        case 'W':
            $newPositions = [];
            
            foreach($positions as $position) {
                [$row, $col] = explode(',', $position);
                
                switch($char) {
                    case 'N':
                        $grid[$row - 1][$col] = '-';
                        $row -= 2;
                        break;
                    case 'S':
                        $grid[$row + 1][$col] = '-';
                        $row += 2;
                        break;
                    case 'E':
                        $grid[$row][$col + 1] = '|';
                        $col += 2;
                        break;
                    case 'W':
                        $grid[$row][$col - 1] = '|';
                        $col -= 2;
                        break;
                }
                
                $grid[$row][$col] = $grid[$row][$col] ?? '.';
                $newPositions[] = $row.','.$col;
            }
            
            $positions = array_values(array_unique($newPositions));
            break;
    }
    
    $pos++;
}

$distances = ['0,0' => 0];
$queue = [[0, 0]];
$index = 0;

while(isset($queue[$index])) {
    [$row, $col] = $queue[$index++];
    
    $directions = [
        [$row - 1, $col, $row - 2, $col],
        [$row + 1, $col, $row + 2, $col],
        [$row, $col - 1, $row, $col - 2],
        [$row, $col + 1, $row, $col + 2]
    ];
    
    foreach($directions as [$doorRow, $doorCol, $nextRow, $nextCol]) {
        if(!isset($grid[$doorRow][$doorCol])) {
            continue;
        }
        
        $nextPosition = $nextRow.','.$nextCol;
        
        if(isset($distances[$nextPosition])) {
            continue;
        }
        
        $distances[$nextPosition] = $distances["$row,$col"] + 1;
        $queue[] = [$nextRow, $nextCol];
    }
}

$valid = array_filter($distances ,function($distance) { return $distance >= 1000;});
$solution = count($valid);

echo 'Solution : ' . $solution . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;