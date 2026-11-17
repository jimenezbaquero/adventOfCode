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
    $orbits[$aux[1]]['parent'] = $aux[0];
    $objects[] = $aux[1];
}

$startObject = $orbits['YOU']['parent'];
$endObject = $orbits['SAN']['parent'];

$queue = [
    [$startObject, 0]
];

$visited = [$startObject];

while (!empty($queue)) {
    
    [$object, $distance] = array_shift($queue);
    
    if ($object === $endObject) {
        $solution = $distance;
        break;
    }
    
    $neighbours = [];
    
    if (isset($orbits[$object]['parent'])) {
        $neighbours[] = $orbits[$object]['parent'];
    }
    
    if (isset($orbits[$object]['objects'])) {
        $neighbours = array_merge($neighbours, $orbits[$object]['objects']);
    }
    
    foreach ($neighbours as $neighbour) {
        if (in_array($neighbour, $visited, true)) {
            continue;
        }
        
        $visited[] = $neighbour;
        $queue[] = [$neighbour, $distance + 1];
    }
}

echo 'Solution : '.$solution.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start, 2).PHP_EOL;
echo 'Memory used: '.number_format(memory_get_peak_usage() / 1024 / 1024, 2).' MB'.PHP_EOL;
echo 'Memory reserved: '.number_format(memory_get_peak_usage(true) / 1024 / 1024, 2).' MB'.PHP_EOL;
