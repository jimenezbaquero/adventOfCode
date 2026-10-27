<?php
$start = microtime(true);
require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$components = [];

foreach ($data as $row => $line) {
    $aux = explode('/',$line);
        $components[] = [
            'start' => $aux[0],
            'end' => $aux[1],
        ];

}

$solution = 0;
foreach ($components as $key => $component) {
    if($component['start'] == '0'){
        $port = $component['end'];
    }else if($component['end'] == '0') {
        $port = $component['start'];
    }else{
        continue;
    }

    $used = [];
    $used[$key] = $component;
    $strength = calculatePath($component, $port, $components, $used);

    if($strength > $solution) {
        $solution = $strength;
    }
}


echo 'Solution: '.$solution.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start,2).PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;

function calculatePath($component, $port, $components, $used) {
    $strength = $component['start'] + $component['end'];
    $best = $strength;
    foreach ($components as $key => $newComponent) {
        if(isset($used[$key])) {
            continue;
        }
        $nextUsed = $used;
        if($newComponent['start'] == $port) {
            $nextUsed[$key] = $newComponent;
            $candidate = $strength + calculatePath($newComponent, $newComponent['end'], $components, $nextUsed);
        }else if($newComponent['end'] == $port) {
            $nextUsed[$key] = $newComponent;
            $candidate = $strength + calculatePath($newComponent, $newComponent['start'], $components, $nextUsed);
        }else{
            continue;
        }
        if($candidate > $best) {
            $best = $candidate;
        }
    }
    return $best;
}
