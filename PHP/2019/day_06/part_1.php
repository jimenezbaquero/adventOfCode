<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$solution = 0;

$orbits = [];
$objects = [];

foreach ($data as $line) {
    $aux = explode(")", $line);
    $orbits[$aux[0]]['objects'][] = $aux[1];
    $objects[] = $aux[1];
}

$initialObjects = array_diff(array_keys($orbits),$objects);

foreach ($initialObjects as $initialObject) {
    calculateLevels($orbits, $initialObject, 0);
}

foreach($orbits as $orbit) {
    $solution+= $orbit['level'];
}

echo 'Solution : '.$solution.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start, 2).PHP_EOL;
echo 'Memory used: '.number_format(memory_get_peak_usage() / 1024 / 1024, 2).' MB'.PHP_EOL;
echo 'Memory reserved: '.number_format(memory_get_peak_usage(true) / 1024 / 1024, 2).' MB'.PHP_EOL;

function calculateLevels(array &$orbits, string $object, int $level):void {
    if(!isset($orbits[$object])) {
        $orbits[$object] = [
            'objects' => [],
            'level' => null
        ];
    }
    $orbits[$object]['level'] = $level;
    foreach ($orbits[$object]['objects'] as $object) {
        calculateLevels($orbits, $object, $level + 1);
    }
}