<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$solution = 0;

$isRule = false;
$state = [];
$rules = [];

foreach ($data as $line) {
    if(empty($line)){
        $isRule = true;
        continue;
    }
    if(!$isRule) {
        $state = str_split(str_replace('initial state: ','',$line));
    }else{
        $aux = explode(' => ', $line);
        $rules[$aux[0]] = $aux[1];
    }
}

$generation = 0;
$min = 0;
$max = count($state) - 1;

while($generation < 20) {
    $stateAux = $state;
    $generation++;
    $minGeneration = $min;
    $maxGeneration = $max;
    for($i = $minGeneration; $i < $maxGeneration + 5; $i++) {
        $block = '';
        for($j = $i - 4; $j <= $i; $j++) {
            $block .= isset($state[$j]) ? $state[$j] : '.';
        }
      
        if(isset($rules[$block])) {
            $stateAux[$i - 2] = $rules[$block];
            if(($i - 2) < $min) {
                $min = $i - 2;
            }
            if(($i - 2) > $max) {
                $max = $i - 2;
            }
        }else{
            $stateAux[$i - 2] = '.';
        }
    }
    $state = $stateAux;
}


$solution = array_sum(array_keys(array_filter($state,function ($s) {return $s == '#';})));

echo 'Solution : ' . $solution . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;
