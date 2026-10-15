<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$ranges = [];

foreach ($data as $line){
    $aux = explode('-', $line);
    $ranges[] = [
        'low' => $aux[0],
        'high' => $aux[1]
    ];
}

usort($ranges, function($a, $b) {return $a['low'] <=> $b['low'];});

cleanRanges($ranges);

$total = 0;

foreach($ranges as $key => $range){
    if($key < count($ranges) - 1) {
        $total += $ranges[$key + 1]['low'] - $range['high'] -1;
    }else {
        $total += 4294967295 - $range['high'];
    }
}

echo 'Solution : '.$total. PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;

function cleanRanges(&$ranges){
    $changes = true;
    while($changes) {
        $changes = false;
        $aux = [];
        foreach ($ranges as $key => $range) {
            if ($key != 0 && (isset($aux[$key-1]) && $range['low'] <= $aux[$key -1]['high'])) {
                continue;
            }
            if($key == count($ranges) - 1){
                $aux[$key] = $range;
                continue;
            }
            
            if ($range['high'] >= $ranges[$key + 1]['low']) {
                if ($range['high'] >= $ranges[$key + 1]['high']) {
                    $aux[$key] = $range;
                    $changes = true;
                } else {
                    $aux[$key] = [
                        'low' => $range['low'],
                        'high' => $ranges[$key + 1]['high']
                    ];
                    $changes = true;
                }
            } else {
                $aux[$key] = $range;
            }
        }
        $ranges = array_values($aux);
    }
}