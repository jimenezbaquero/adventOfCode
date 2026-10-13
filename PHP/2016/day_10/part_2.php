<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

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
    addChip($containers['bot'], intval($assign['bot']), $assign['value']);
}

$doneInstructions = 1;

while($doneInstructions > 0) {
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
        $number = $instruction['high']['number'];
        
        if ($destiny == 'bot') {
            addChip($containers['bot'], $number, $max);
        } else {
            $containers['output'][$number][] = $max;
        }
        
        $destiny = $instruction['low']['type'];
        $number = $instruction['low']['number'];
        
        if ($destiny == 'bot') {
            addChip($containers['bot'], $number, $min);
        } else {
            $containers['output'][$number][] = $min;
        };
        
        $doneInstructions++;
    }
}

$solution = $containers['output'][0][0] * $containers['output'][1][0] * $containers['output'][2][0];

echo 'Solution: '.$solution.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start,2);

function addChip(array &$container, int $bot, int $value): void {
    if (!isset($container[$bot])) {
        $container[$bot] = [$value];
        return;
    }
    
    if ($value < $container[$bot][0]) {
        array_unshift($container[$bot], $value);
    } else {
        $container[$bot][] = $value;
    }
}