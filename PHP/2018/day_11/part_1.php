<?php

$start = microtime(true);

require_once('../../functions.php');

//$serial = 18;

$serial = 1309;


$grid = [];

for($col = 1; $col <= 300; $col++) {
    for ($row = 1; $row <= 300; $row++) {
        $rack = $col + 10;
        $level = $rack * $row;
        $level = $level + $serial;
        $level = $level * $rack;
        $digit = intdiv(($level % 1000),100);
        $power = $digit - 5;
        $grid[$col][$row] = $power;
    }
}

$max = -PHP_INT_MAX;
for($col = 1; $col <= 298; $col++) {
    for ($row = 1; $row <= 298; $row++) {
        $sum = 0;
        for ($i = 0; $i < 3; $i++) {
            for ($j = 0; $j < 3; $j++) {
                $sum += $grid[$col + $i][$row + $j];
            }
        }
        if($sum > $max) {
            $max = $sum;
            $solution = $col.','.$row;
        }
    }
}

echo 'Solution: '.$solution.PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;
