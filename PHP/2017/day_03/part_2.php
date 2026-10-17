<?php

$start = microtime(true);

require_once('../../functions.php');

$input = 265149;

$x = 0;
$y = 0;
$spiral[$x][$y] = 1;
$count = 2;
$number = 1;

while ($number <= $input) {
    [$x, $y] = determinePosition($count);
    $number = sumNeighbors($x,$y, $spiral);
    $spiral[$x][$y] = $number;
    $count++;
    
}


echo 'Solution : '.$number.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start, 2).PHP_EOL;
echo 'Memory used: '.number_format(memory_get_peak_usage() / 1024 / 1024, 2).' MB'.PHP_EOL;
echo 'Memory reserved: '.number_format(memory_get_peak_usage(true) / 1024 / 1024, 2).' MB'.PHP_EOL;

function determinePosition($input)
{
    $level = 1;
    $value = 1;
    
    while ($input > $value ** 2) {
        $level++;
        $value += 2;
    }
    
    $diff = ($value ** 2) - $input;
    $sideLength = $value - 1;
    $radius = $level - 1;
    
    $side = intdiv($diff, $sideLength);
    $position = $diff % $sideLength;
    
    switch ($side) {
        case 0:
            $x = $radius - $position;
            $y = -$radius;
            break;
        
        case 1:
            $x = -$radius;
            $y = -$radius + $position;
            break;
        
        case 2:
            $x = -$radius + $position;
            $y = $radius;
            break;
        
        case 3:
            $x = $radius;
            $y = $radius - $position;
            break;
    }
    
    return [$x, $y];
    
}

function sumNeighbors($x, $y, $spiral){
    $directions = [-1, 0, 1];
    $sum = 0;
    foreach ($directions as $directionRow) {
        foreach ($directions as $directionColumn) {
            if ($directionRow == 0 && $directionColumn == 0){
                continue ;
            }
            $newX = $x + $directionColumn;
            $newY = $y + $directionRow;
            if(isset($spiral[$newX][$newY])){
                $sum += $spiral[$newX][$newY];
            }
        }
    }
    return $sum;
}
