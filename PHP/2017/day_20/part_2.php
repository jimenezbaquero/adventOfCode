<?php
//Esto es una solución que se llega por aproximación. Lo correcto sería:
//Calcular los posibles instantes de colisión entre las partículas que siguen vivas.
//Identificar el instante de colisión más próximo.
//Avanzar hasta ese instante.
//Eliminar simultáneamente todas las partículas que coincidan en una misma posición en ese instante.
//Recalcular las posibles colisiones entre las supervivientes.
//Repetir hasta que no existan más colisiones.

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
}
$iteration = 0;
while(count($particles) > 1 && $iteration++ < 1000) {
    echo 'Iteration: '.$iteration.' Partículas: '.count($particles).PHP_EOL;
    $particles = cleanCollisions($particles);
    $particles = calculatePositions($particles);
}

$solution = count($particles);

echo 'Solution: '.$solution.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start,2).PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;

function cleanCollisions($particles) {
    $visits = [];
    $repeats = [];
    foreach ($particles as $particle) {
        $label = $particle['position'][0].','.$particle['position'][1].','.$particle['position'][2];
        if(!isset($visits[$label])) {
            $visits[$label] = true;
        }else{
            $repeats[] = $label;
        }
    }
    $repeats = array_unique($repeats);
    return array_filter($particles, function($particle) use ($repeats) {
        $label = $particle['position'][0].','.$particle['position'][1].','.$particle['position'][2];
        return !in_array($label, $repeats);
    });
}

function calculatePositions($particles) {
    $aux = [];
    foreach ($particles as $key => $particle) {
        for ($i = 0; $i < 3; $i++) {
            $particle['velocity'][$i] += $particle['acceleration'][$i];
            $particle['position'][$i] += $particle['velocity'][$i];
        }

        $aux[$key] = [
            'position' => [$particle['position'][0], $particle['position'][1], $particle['position'][2]],
            'velocity' => [$particle['velocity'][0], $particle['velocity'][1], $particle['velocity'][2]],
            'acceleration' => [$particle['acceleration'][0], $particle['acceleration'][1], $particle['acceleration'][2]],
        ];
    }
    return $aux;
}
