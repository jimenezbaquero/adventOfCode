<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$instructions = [];

$state0['p'] = 0;
$state1['p'] = 1;

$queue0 = [];
$queue1 = [];

$execute0 = false;
$execute1 = false;

foreach ($data as $line) {
    $aux = explode(" ", $line);
    $instructions[] = [
        'operation' => $aux[0],
        'value' => $aux[1],
        ...(isset($aux[2])? ['value2' => $aux[2]]:[])
    ];
}

$instructionNumber = [ 0, 0];
$hasNextInstruction = [true, true];
$solution = 0;

while($hasNextInstruction[0] || $hasNextInstruction[1]) {
    if($hasNextInstruction[0]) {
        [$instructionNumber[0], $hasNextInstruction[0], $execute0] = executeInstruction(0, $instructionNumber[0], $instructions, $state0, $queue0, $queue1, $solution);
    }
    if($hasNextInstruction[1]) {
        [$instructionNumber[1], $hasNextInstruction[1], $execute1] = executeInstruction(1, $instructionNumber[1], $instructions, $state1, $queue1, $queue0, $solution);
    }
    if(!$execute0 && !$execute1) {
        break;
    }
}



echo 'Solution: '.$solution.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start,2).PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;

function executeInstruction ($program, $instructionNumber, $instructions, &$state, &$ownQueue, &$opponentQueue, &$solution){
    $execute = true;
    $instruction = $instructions[$instructionNumber];
    switch ($instruction['operation']) {
        case 'snd';
            if($program == 1){
                $solution++;
            }
            $instructionNumber++;
            if (is_numeric($instruction['value'])) {
                $value = intval($instruction['value']);
            }else{
                if(!isset($state[$instruction['value']])){
                    $state[$instruction['value']] = 0;
                }
                $value = $state[$instruction['value']];
            }
            $opponentQueue[] = $value ;
            break;
        case 'set':
            $instructionNumber++;
            if (is_numeric($instruction['value2'])) {
                $value2 = intval($instruction['value2']);
            }else{
                if(!isset($state[$instruction['value2']])){
                    $state[$instruction['value2']] = 0;
                }
                $value2 = $state[$instruction['value2']];
            }
            $state[$instruction['value']] = $value2;
            break;
        case 'add':
            $instructionNumber++;
            if (is_numeric($instruction['value2'])) {
                $value2 = intval($instruction['value2']);
            }else{
                if(!isset($state[$instruction['value2']])){
                    $state[$instruction['value2']] = 0;
                }
                $value2 = $state[$instruction['value2']];
            }
            $state[$instruction['value']] += $value2;
            break;
        case 'mul':
            $instructionNumber++;
            if (is_numeric($instruction['value2'])) {
                $value2 = intval($instruction['value2']);
            }else{
                if(!isset($state[$instruction['value2']])){
                    $state[$instruction['value2']] = 0;
                }
                $value2 = $state[$instruction['value2']];
            }
            $state[$instruction['value']] *= $value2;
            break;
        case 'mod':
            $instructionNumber++;
            if (is_numeric($instruction['value2'])) {
                $value2 = intval($instruction['value2']);
            }else{
                if(!isset($state[$instruction['value2']])){
                    $state[$instruction['value2']] = 0;
                }
                $value2 = $state[$instruction['value2']];
            }
            $state[$instruction['value']] %= $value2;
            break;
        case 'rcv';
            if(!empty($ownQueue)) {
                $instructionNumber++;
                $value = array_shift($ownQueue);
                if (!isset($state[$instruction['value']])) {
                    $state[$instruction['value']] = 0;
                }
                $state[$instruction['value']] = $value;
            }else{
                $execute = false;
            }
            break;
        case 'jgz':
            if (is_numeric($instruction['value'])) {
                $value = intval($instruction['value']);
            } else {
                if(!isset($state[$instruction['value']])){
                    $state[$instruction['value']] = 0;
                }
                $value = $state[$instruction['value']];
            }
            if (is_numeric($instruction['value2'])) {
                $value2 = intval($instruction['value2']);
            } else {
                if(!isset($state[$instruction['value2']])){
                    $state[$instruction['value2']] = 0;
                }
                $value2 = $state[$instruction['value2']];
            }
            if ($value <= 0) {
                $instructionNumber++;
            } else {
                $instructionNumber = $instructionNumber + $value2;
            }
            break;
    }
    $hasNextInstruction = !($instructionNumber < 0 || $instructionNumber >= count($instructions));
    return [$instructionNumber, $hasNextInstruction, $execute];
}
