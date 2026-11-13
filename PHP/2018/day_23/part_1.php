<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$solution = 0;

$nanobots = [];
$maxRadius = 0;
$center = null;

foreach ($data as $line) {
    $aux = explode(">, ", $line);
    $aux1 = explode(',',str_replace('pos=<', '', $aux[0]));
    $radius = intval(str_replace('r=', '', $aux[1]));
    $x = intval($aux1[0]);
    $y = intval($aux1[1]);
    $z = intval($aux1[2]);
    $nanobots[] = [
        'x' => $x,
        'y' => $y,
        'z' => $z,
        'radius' =>$radius
    ];
    
    if($radius > $maxRadius) {
        $maxRadius = $radius;
        $center = [
            'x' => $x,
            'y' => $y,
            'z' => $z
        ];
    }
}

foreach ($nanobots as $nanobot) {
    if(calculateDistance($nanobot, $center) <= $maxRadius) {
        $solution++;
    }
}


echo 'Solution : ' . $solution . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;


function calculateDistance($nanobot, $center) {
    return abs($center['x'] - $nanobot['x']) + abs($center['y'] - $nanobot['y']) + abs($center['z'] - $nanobot['z']);
}