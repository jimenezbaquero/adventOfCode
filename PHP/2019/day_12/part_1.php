<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$steps = 1000;

$solution = 0;

$moons = [];

foreach ($data as $line) {
    $auxPos = explode(', ', str_replace(['<','>','x=','y=','z='],'',$line));
    $auxPos = [
        'x' => intval(trim($auxPos[0])),
        'y' => intval(trim($auxPos[1])),
        'z' => intval(trim($auxPos[2])),
    ];
    $auxVel = [
        'x' => 0,
        'y' => 0,
        'z' => 0,
    ];
    $moons[] = [
        'pos' => $auxPos,
        'vel' => $auxVel
    ];
}

for ($i=0; $i < $steps; $i++) {
    $aux = [];
    foreach ($moons as $key => $moon) {
        $aux[$key] = calculateData($key, $moons);
    }
    $moons = $aux;
}

foreach ($moons as $key => $moon) {
    $aux = array_values($moon['pos']);
    $aux = array_map(function($value) {return abs($value);}, $aux);
    $sumPos = array_sum($aux);
    $aux = array_values($moon['vel']);
    $aux = array_map(function($value) {return abs($value);}, $aux);
    $sumVel = array_sum($aux);
    $solution += $sumPos * $sumVel;
}

echo 'Solution : '.$solution.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start, 2).PHP_EOL;
echo 'Memory used: '.number_format(memory_get_peak_usage() / 1024 / 1024, 2).' MB'.PHP_EOL;
echo 'Memory reserved: '.number_format(memory_get_peak_usage(true) / 1024 / 1024, 2).' MB'.PHP_EOL;

function calculateData($key, $moons) {
    $coords = [
        'x',
        'y',
        'z'
    ];
    $moon = $moons[$key];
    $aux = $moon;
    foreach ($coords as $coord) {
        $position = $moon['pos'][$coord];
        $velocity = $moon['vel'][$coord];
        foreach ($moons as $key2 => $moon2) {
            if ($key2 == $key) {
                continue;
            }
            if($moon2['pos'][$coord] == $moon['pos'][$coord]){
                continue;
            }
            $velocity += $moon2['pos'][$coord] > $moon['pos'][$coord] ? 1 : -1;
        }
        $position += $velocity;
        $aux['pos'][$coord] = $position;
        $aux['vel'][$coord] = $velocity;
    }
    return $aux;
}
