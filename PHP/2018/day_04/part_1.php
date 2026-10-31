<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$solution = 0;
$instructions = [];
$guards = [];

foreach ($data as $line) {
    $instruction = null;
    $aux = explode("] ", str_replace(['[','#'],'',$line));
    $key = str_replace(' ',',',$aux[0]);
    $aux = explode(' ',$aux[1]);
    if($aux[0] === 'Guard') {
        $instruction = $aux[1];
    }else{
        $instruction = $aux[0];
    }
    $instructions[$key] = $instruction;
}

ksort($instructions);

$guard = null;
$time = 0;
$max = 0;
$maxGuard = null;

foreach ($instructions as $key => $instruction) {
    $minute = intval(explode(':',$key)[1]);
    switch ($instruction) {
        case 'wakes':
            $guards[$guard]['time']+= $minute - $time;
            $guards[$guard]['sections'][]= [
                'start' => $time,
                'end' => $minute - 1
            ];
            if($max < $guards[$guard]['time']) {
                $max = $guards[$guard]['time'];
                $maxGuard = $guard;
            }
            break;
        case 'falls':
            $time = $minute;
            break;
        default:
            $guard = $instruction;
            if(!isset($guards[$guard])) {
                $guards[$guard]['time'] = 0;
            }
    }
}

$minutes = [];
$maxMinutes = 0;
foreach ($guards[$maxGuard]['sections'] as $section) {
    for($i = $section['start']; $i <= $section['end']; $i++) {
        if(!isset($minutes[$i])) {
            $minutes[$i] = 0;
        }
        $minutes[$i]++;
        if($maxMinutes < $minutes[$i]) {
            $maxMinutes = $minutes[$i];
            $solution = $i;
        }
    }
}

$solution = $maxGuard * $solution;

echo 'Solution : ' . $solution . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;

