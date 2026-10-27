<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$instructions = [];

$state = [
    'a' => 1,
    'b' => 0,
    'c' => 0,
    'd' => 0,
    'e' => 0,
    'f' => 0,
    'g' => 0,
    'h' => 0
];

foreach ($data as $line) {
    $aux = explode(" ", $line);
    $instructions[] = [
        'operation' => $aux[0],
        'value' => $aux[1],
        ...(isset($aux[2]) ? ['value2' => $aux[2]] : [])
    ];
}

$instructionNumber = 0;
$hasNextInstruction = true;

while ($hasNextInstruction) {
    $instruction = $instructions[$instructionNumber];
    $pattern = findPattern($instructionNumber, $instructions);
    if ($pattern !== false) {
        $number = $state[$pattern['number']];

        $state[$pattern['flag']] = isPrime($number) ? 1 : 0;

        $state[$pattern['divisor1']] = $number + 1;
        $state[$pattern['divisor2']] = $number + 1;
        $state[$pattern['temp']] = 1;

        $instructionNumber += 16;
    } else {
        switch ($instruction['operation']) {
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
                if(!isset($state[$instruction['value']])){
                    $state[$instruction['value']] = 0;
                }
                $state[$instruction['value']] = $value2;
                break;
            case 'sub':
                $instructionNumber++;
                if (is_numeric($instruction['value2'])) {
                    $value2 = intval($instruction['value2']);
                }else{
                    if(!isset($state[$instruction['value2']])){
                        $state[$instruction['value2']] = 0;
                    }
                    $value2 = $state[$instruction['value2']];
                }
                if(!isset($state[$instruction['value']])){
                    $state[$instruction['value']] = 0;
                }
                $state[$instruction['value']] -= $value2;
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
                if(!isset($state[$instruction['value']])){
                    $state[$instruction['value']] = 0;
                }
                $state[$instruction['value']] *= $value2;
                break;
            case 'jnz':
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
                if ($value == 0) {
                    $instructionNumber++;
                } else {
                    $instructionNumber = $instructionNumber + $value2;
                }
                break;
        }

    }
    if ($instructionNumber < 0 || $instructionNumber >= count($instructions)) {
        $hasNextInstruction = false;
    }
}
$solution = $state['h'];

echo 'Solution: ' . $solution . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;


function findPattern($instructionNumber, $instructions) {
    if (!isset($instructions[$instructionNumber + 15])) {
        return false;
    }

    $pattern = [
        ['set', null, '1'],
        ['set', null, '2'],
        ['set', null, '2'],
        ['set', null, null],
        ['mul', null, null],
        ['sub', null, null],
        ['jnz', null, '2'],
        ['set', null, '0'],
        ['sub', null, '-1'],
        ['set', null, null],
        ['sub', null, null],
        ['jnz', null, '-8'],
        ['sub', null, '-1'],
        ['set', null, null],
        ['sub', null, null],
        ['jnz', null, '-13'],
    ];

    foreach ($pattern as $offset => $expected) {
        $instruction = $instructions[$instructionNumber + $offset];

        if (
            $instruction['operation'] != $expected[0] ||
            ($expected[1] !== null && $instruction['value'] != $expected[1]) ||
            ($expected[2] !== null && ($instruction['value2'] ?? null) != $expected[2])
        ) {
            return false;
        }
    }

    $instructionsBlock = array_slice($instructions, $instructionNumber, 16);

    $flag = $instructionsBlock[0]['value'];
    $divisor1 = $instructionsBlock[1]['value'];
    $divisor2 = $instructionsBlock[2]['value'];
    $temp = $instructionsBlock[3]['value'];
    $number = $instructionsBlock[5]['value2'];

    if (
        $instructionsBlock[3]['value2'] != $divisor1 ||
        $instructionsBlock[4]['value'] != $temp ||
        $instructionsBlock[4]['value2'] != $divisor2 ||
        $instructionsBlock[5]['value'] != $temp ||
        $instructionsBlock[6]['value'] != $temp ||
        $instructionsBlock[7]['value'] != $flag ||
        $instructionsBlock[8]['value'] != $divisor2 ||
        $instructionsBlock[9]['value'] != $temp ||
        $instructionsBlock[9]['value2'] != $divisor2 ||
        $instructionsBlock[10]['value'] != $temp ||
        $instructionsBlock[10]['value2'] != $number ||
        $instructionsBlock[11]['value'] != $temp ||
        $instructionsBlock[12]['value'] != $divisor1 ||
        $instructionsBlock[13]['value'] != $temp ||
        $instructionsBlock[13]['value2'] != $divisor1 ||
        $instructionsBlock[14]['value'] != $temp ||
        $instructionsBlock[14]['value2'] != $number ||
        $instructionsBlock[15]['value'] != $temp
    ) {
        return false;
    }

    if (count(array_unique([$flag, $divisor1, $divisor2, $temp, $number])) != 5) {
        return false;
    }

    return [
        'flag' => $flag,
        'divisor1' => $divisor1,
        'divisor2' => $divisor2,
        'temp' => $temp,
        'number' => $number,
    ];
}


function isPrime($number) {
    for ($i = 2; $i * $i <= $number; $i++) {
        if ($number % $i == 0) {
            return false;
        }
    }
    return true;
}
