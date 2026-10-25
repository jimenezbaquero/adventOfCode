<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$instructions = [];

$state = [];

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
$sound = null;

while($hasNextInstruction) {
$instruction = $instructions[$instructionNumber];
    switch ($instruction['operation']) {
        case 'snd';
            $instructionNumber++;
            $sound = $state[$instruction['value']];
            break;
        case 'set':
            $instructionNumber++;
            if (is_numeric($instruction['value2'])) {
                $value2 = intval($instruction['value2']);
            }else{
                $value2 = $state[$instruction['value2']];
            }
            $state[$instruction['value']] = $value2;
            break;
        case 'add':
            $instructionNumber++;
            if (is_numeric($instruction['value2'])) {
                $value2 = intval($instruction['value2']);
            }else{
                $value2 = $state[$instruction['value2']];
            }
            $state[$instruction['value']] += $value2;
            break;
        case 'mul':
            $instructionNumber++;
            if (is_numeric($instruction['value2'])) {
                $value2 = intval($instruction['value2']);
            }else{
                $value2 = $state[$instruction['value2']];
            }
            $state[$instruction['value']] *= $value2;
            break;
        case 'mod':
            $instructionNumber++;
            if (is_numeric($instruction['value2'])) {
                $value2 = intval($instruction['value2']);
            }else{
                $value2 = $state[$instruction['value2']];
            }
            $state[$instruction['value']] %= $value2;
            break;
        case 'rcv';
            $instructionNumber++;
            $state[$instruction['value']] = $sound;
            if($sound != 0){
                $solution = $sound;
                break 2;
            }
            break;
        case 'jgz':
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
    }
    
    if($instructionNumber < 0 || $instructionNumber >= count($instructions)) {
        $hasNextInstruction = false;
    }
}



echo 'Solution: '.$solution.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start,2).PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;
