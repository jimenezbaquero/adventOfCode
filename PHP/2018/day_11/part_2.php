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
        $grid[$col][$row]['power'] = $power;
    }
}



for ($col = 0; $col <= 300; $col++) {
    $grid[$col][0]['sum'] = 0;
}

for ($row = 0; $row <= 300; $row++) {
    $grid[0][$row]['sum'] = 0;
}


for ($col = 1; $col <= 300; $col++) {
    for ($row = 1; $row <= 300; $row++) {
        $grid[$col][$row]['sum'] =
            $grid[$col][$row]['power']
            + $grid[$col - 1][$row]['sum']
            + $grid[$col][$row - 1]['sum']
            - $grid[$col - 1][$row - 1]['sum'];
    }
}

$max = -PHP_INT_MAX;
for($col = 1; $col <= 300; $col++) {
    for ($row = 1; $row <= 300; $row++) {
        for($size = 1; $size < min(300 - $col, 300 - $row); $size++) {
            $sum = $grid[$col + $size - 1][$row + $size - 1]['sum'] -
                $grid[$col - 1][$row + $size - 1]['sum'] -
                $grid[$col + $size - 1][$row - 1]['sum'] +
                $grid[$col - 1][$row - 1]['sum'];
            
            if ($sum > $max) {
                $max = $sum;
                $solution = $col . ',' . $row. ',' . $size;
            }
        }
    }
}


echo 'Solution: '.$solution.PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;


//FUERZA BRUTA
$start = microtime(true);

$max = -PHP_INT_MAX;
for($col = 1; $col <= 300; $col++) {
    for ($row = 1; $row <= 300; $row++) {
        $sum = 0;
        for($size = 1; $size <= min(300 - $col, 300 - $row); $size++) {
            for ($i = 0; $i < $size; $i++) {
                    $sum += $grid[$col + $i][$row + $size - 1]['power'];
                }

            for ($j = 0; $j < $size - 1; $j++) {
                $sum += $grid[$col + $size - 1][$row + $j]['power'];
            }

            if ($sum > $max) {
                $max = $sum;
                $solution = $col . ',' . $row. ',' . $size;
            }
        }
    }
}

echo 'Solution: '.$solution.PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;