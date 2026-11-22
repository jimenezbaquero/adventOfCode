<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$velocities = [];
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

$coords = ['x','y','z'];

$position0 = $moons;

$cycleX = 0;
$cycleY = 0;
$cycleZ = 0;

while($cycleX == 0 || $cycleY == 0 || $cycleZ == 0){
    $solution++;
    $aux = [];

    foreach ($moons as $key => $moon) {
        $aux[$key] = calculateData($key, $moons, $coords);
    }
    $moons = $aux;
    $position = determinePosition($moons, $coords, $position0);

    if($cycleX == 0){
        if($position['x']){
            $cycleX = $solution;
            unset($coords[array_search('x', $coords)]);
        }
    }

    if($cycleY == 0){
        if($position['y']){
            $cycleY = $solution;
            unset($coords[array_search('y', $coords)]);
        }
    }

    if($cycleZ == 0){
        if($position['z']){
            $cycleZ = $solution;
            unset($coords[array_search('z', $coords)]);
        }
    }
}

$solution = mcmArray([$cycleX,$cycleY,$cycleZ]);

echo 'Solution : '.$solution.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start, 2).PHP_EOL;
echo 'Memory used: '.number_format(memory_get_peak_usage() / 1024 / 1024, 2).' MB'.PHP_EOL;
echo 'Memory reserved: '.number_format(memory_get_peak_usage(true) / 1024 / 1024, 2).' MB'.PHP_EOL;

function calculateData($key, $moons, $coords) {
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

function determinePosition($moons, $coords, $initialMoons) {
    $positions = [];
    foreach ($coords as $coord) {
        $correct = true;
        foreach ($moons as $key => $moon) {
            if($moon['pos'][$coord] != $initialMoons[$key]['pos'][$coord] || $moon['vel'][$coord] != $initialMoons[$key]['vel'][$coord]){
                $correct = false;
                break;
            }
        }
        $positions[$coord] = $correct;
    }

    return $positions;

}
