<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$solution = 0;
$erosions = [];

$depth = intval(str_replace('depth: ', '', $data[0]));
$target = array_map(function($v) {return intval($v);},explode(',',str_replace('target: ','',$data[1])));

for($row = 0; $row <= $target[1]; $row++) {
    for($col = 0; $col <= $target[0]; $col++) {
        if($row == 0 && $col == 0 || $row == $target[1] && $col == $target[0]) {
            $index = 0;
        }
        else if($row == 0) {
            $index = $col * 16807;
        }else if($col == 0) {
            $index = $row * 48271;
        }else{
            $index = $erosions[$col][$row - 1] * $erosions[$col - 1][$row];
        }
        
        $erosion = ($index + $depth) % 20183;
        $erosions[$col][$row] = $erosion;
        $type = $erosion % 3;
        
        $solution += $type;
    }
}

echo 'Solution : ' . $solution . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;
