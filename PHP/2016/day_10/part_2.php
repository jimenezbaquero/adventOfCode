<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$maxFind = 61;
$minFind = 17;

$solution = null;

$assigns = [];
$instructions = [];
$containers = [
    'bot' => [],
    'output' => []
];

foreach ($data as $line) {
    $aux = explode(" ", $line);
    if($aux[0] == 'value') {
        $assigns[] = [
            'bot' => $aux[5],
            'value' => intval($aux[1])
        ];
    }else{
        $instructions[] = [
            'bot' => $aux[1],
            'high' => [
                'type' => $aux[10],
                'number' => intval($aux[11])
            ],
            'low' => [
                'type' => $aux[5],
                'number' => intval($aux[6])
            ]
        ];
    }
}

foreach ($assigns as $assign) {
    $containers['bot'][$assign['bot']][] = $assign['value'];
}

$containers  = orderContainers($containers);

$i = 1;
$doneInstructions = 1;

while($doneInstructions > 0) {
    $aux = [];
    $doneInstructions = 0;
    foreach ($instructions as $instruction) {
        if (!isset($containers['bot'][$instruction['bot']])) {
            continue;
        }
        
        if (count($containers['bot'][$instruction['bot']]) !== 2) {
            continue;
        }
        
        $max = array_pop($containers['bot'][$instruction['bot']]);
        $min = array_shift($containers['bot'][$instruction['bot']]);
        
        $destiny = $instruction['high']['type'];
        $containers[$destiny][$instruction['high']['number']][] = $max;
        
        $destiny = $instruction['low']['type'];
        $containers[$destiny][$instruction['low']['number']][] = $min;
        
        $containers = orderContainers($containers);
        
        $doneInstructions++;
    }
}

$solution = $containers['output'][0][0] * $containers['output'][1][0] * $containers['output'][2][0];

echo 'Solution: '.$solution.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start,2);

function orderContainers(array $containers): array {
    $aux = [];
    $aux['bot'] = orderContainer($containers['bot']);
    $aux['output'] = orderContainer($containers['output']);
    return $aux;
}

function orderContainer(array $container): array {
    $aux = [];
    foreach ($container as $key => $values) {
        asort($values);
        $aux[$key] = $values;
    }
    ksort($aux);
    return $aux;
}