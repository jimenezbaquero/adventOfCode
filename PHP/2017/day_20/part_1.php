<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');
$solution = '';

$particles = [];
$solution = null;
$minAcceleration = PHP_INT_MAX;

foreach ($data as $line) {
    $aux = explode(", ", $line);
    $particle = [
        'position' => explode(',', str_replace(['p=<','>'],'',$aux[0])),
        'velocity' => explode(',', str_replace(['v=<','>'],'',$aux[1])),
        'acceleration' => explode(',', str_replace(['a=<','>'],'',$aux[2])),
    ];
    $particles[] = $particle;
    $velocity =  abs($particle['acceleration'][0]) + abs($particle['acceleration'][1]) + abs($particle['acceleration'][2]);
    if($velocity < $minAcceleration) {
        $minAcceleration = $velocity;
    }
}

$particles = array_filter($particles, function($particle) use ($minAcceleration) {
    return abs($particle['acceleration'][0]) + abs($particle['acceleration'][1]) + abs($particle['acceleration'][2]) == $minAcceleration;
});

if(count($particles) != 1) {
    $minVelocity = PHP_INT_MAX;
    foreach ($particles as $particle) {
        $velocity =  abs($particle['velocity'][0]) + abs($particle['velocity'][1]) + abs($particle['velocity'][2]);
        if($velocity < $minVelocity) {
            $minVelocity = $velocity;
        }
    }
    $particles = array_filter($particles, function($particle) use ($minVelocity) {
        return abs($particle['velocity'][0]) + abs($particle['velocity'][1]) + abs($particle['velocity'][2]) == $minVelocity;
    });
    if(count($particles) != 1) {
        foreach ($particles as $key => $particle) {
            $minDistance = PHP_INT_MAX;
            do{
                for($i = 0; $i < 3; $i++){
                    $particle['velocity'][$i] += $particle['acceleration'][$i];
                    $particle['position'][$i] += $particle['velocity'][$i];
                }
                $distance = abs($particle['position'][0]) + abs($particle['position'][1]) + abs($particle['position'][2]);
                if($distance <= $minDistance){
                    $minDistance = $distance;
                }
                $needContinue = needContinue($particle);
            }while($needContinue);
            if($minDistance < $min){
                $min = $minDistance;
                $solution = $key;
            }
        }
    }else{
        $solution =array_keys($particles)[0];
    }
}else{
    $solution = array_keys($particles)[0];
}

echo 'Solution: '.$solution.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start,2).PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;

function needContinue($particle){
    for($i = 0; $i < 3; $i++) {
        if(sign($particle['acceleration'][$i]) == 0){
            if(sign($particle['velocity'][$i]) != 0) {
                if (sign($particle['position'][$i]) != sign($particle['velocity'][$i])) {
                    return true;
                }
            }
            break;
        }
        if (sign($particle['velocity'][$i]) != sign($particle['acceleration'][$i])) {
            return true;
        }
        if (sign($particle['position'][$i]) != sign($particle['velocity'][$i])) {
            return true;
            
        }
    }
    return false;
}