<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$instructions = [];

$state = [
    'a' => 7,
    'b' => 0,
    'c' => 0,
    'd' => 0,
];

foreach ($data as $line) {
    $aux = explode(" ", $line);
    $instructions[] = [
        'operation' => $aux[0],
        'value' => $aux[1],
        ...(isset($aux[2])? ['value2' => $aux[2]]:[])
    ];
}

$instructionNumber = 0;
$hasNextInstruction = true;
$iteration = 0;

while($hasNextInstruction) {
$instruction = $instructions[$instructionNumber];
    switch ($instruction['operation']) {
        case 'cpy':
            $instructionNumber++;
            if (is_numeric($instruction['value2'])) {
                break;
            }
            if (is_numeric($instruction['value'])) {
                $value = intval($instruction['value']);
            } else {
                $value = $state[$instruction['value']];
            }
            $state[$instruction['value2']] = $value;
            break;
        case 'inc';
            $state[$instruction['value']]++;
            $instructionNumber++;
            break;
        case 'dec':
            $state[$instruction['value']]--;
            $instructionNumber++;
            break;
        case 'jnz':
            if (is_numeric($instruction['value'])) {
                $value = intval($instruction['value']);
            } else {
                $value = $state[$instruction['value']];
            }
            if (is_numeric($instruction['value2'])) {
                $value2 = intval($instruction['value2']);
            } else {
                $value2 = $state[$instruction['value2']];
            }
            if ($value == 0) {
                $instructionNumber++;
            } else {
                $instructionNumber = $instructionNumber + $value2;
            }
            break;
        case 'tgl':
            if (is_numeric($instruction['value'])) {
                $value = intval($instruction['value']);
            } else {
                $value = $state[$instruction['value']];
            }
            if(!isset($instructions[$instructionNumber + $value]) || $value == 0) {
                $instructionNumber++;
                break;
            }
            $destinyInstruction = $instructionNumber + $value;
            $auxInstruction = $instructions[$destinyInstruction];
            if (!isset($auxInstruction['value2'])) {
                if ($auxInstruction['operation'] == 'inc') {
                    $instructions[$destinyInstruction]['operation'] = 'dec';
                } else {
                    $instructions[$destinyInstruction]['operation'] = 'inc';
                }
            }else{
                if ($auxInstruction['operation'] == 'jnz') {
                    $instructions[$destinyInstruction]['operation'] = 'cpy';
                }else{
                    $instructions[$destinyInstruction]['operation'] = 'jnz';
                }
            }
            $instructionNumber++;
            break;
    }
    
    if($instructionNumber < 0 || $instructionNumber >= count($instructions)) {
        $hasNextInstruction = false;
    }
    $iteration++;
}

$solution = $state['a'];

echo 'Solution: '.$solution.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start,2).PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;
