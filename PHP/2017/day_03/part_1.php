<?php

$start = microtime(true);

require_once('../../functions.php');

$input = 265149;

$level = 1;
$value = 1;

while ($input > $value ** 2) {
    $level++;
    $value += 2;
}

$diff = ($value ** 2) - $input;
$sideLength = $value - 1;

$side = intdiv($diff , $sideLength);
$position = $diff % $sideLength;


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
$distance = abs($x) + abs($y);


echo 'Solution : '.$distance.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start, 2).PHP_EOL;
echo 'Memory used: '.number_format(memory_get_peak_usage() / 1024 / 1024, 2).' MB'.PHP_EOL;
echo 'Memory reserved: '.number_format(memory_get_peak_usage(true) / 1024 / 1024, 2).' MB'.PHP_EOL;

