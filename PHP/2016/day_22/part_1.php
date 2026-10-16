<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$nodes = [];

foreach ($data as $line) {
    if(!str_starts_with($line,'/dev/grid' )) {
        continue;
    }
    $line = str_replace(['/dev/grid/node-','x','y','T','%'], '', $line);
    $line = preg_replace('/\s+/', '-', $line);
    $aux = explode('-',$line);

    $nodes[$aux[0].','.$aux[1]] = [
        'x' => intval($aux[0]),
        'y' => intval($aux[1]),
        'size' => intval($aux[2]),
        'used' => intval($aux[3]),
        'available' => intval($aux[4]),
        'percent' => intval($aux[5]),
    ];
}

$pairs = 0;
foreach ($nodes as $keyA => $nodeA) {
    if ($nodeA['used'] == 0) {
        continue;
    }
    
    foreach ($nodes as $keyB => $nodeB) {
        if ($keyA == $keyB) {
            continue;
        }
        
        if ($nodeA['used'] <= $nodeB['available']) {
            $pairs++;
        }
    }
}

echo 'Solution : '.$pairs. PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;

