<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$solution = 0;
$instructions = [];
$ip = null;

foreach ($data as $line) {
    if(str_starts_with($line, '#')){
        $ip = intval(str_replace('#ip ', '', $line));
    }else{
        $aux = explode(' ', $line);
        $instructions[] = [
            'operator' => $aux[0],
            'value1' => intval($aux[1]),
            'value2' => intval($aux[2]),
            'destiny' => intval($aux[3]),
        ];
    }
}

$state = [
    0,
    0,
    0,
    0,
    0,
    0
];

$pointer = [
    'register' => $ip,
    'value' => 0
];

$numInstructions = count($instructions);
$inRange = true;
$contador = 0;
$values = [];
$prevValue = null;
$numPattern = findPattern($instructions);
while ($inRange) {
    if($pointer['value'] == $numPattern){
        $division = intdiv($state[2], 256);
        $state = [
            $state[0],
            $state[1],
            $division,
            7,
            1,
            $division
        ];
        $pointer['value'] = 8;
    }else {
        $instruction = $instructions[$pointer['value']];
        if ($pointer['value'] == 28) {
            if (isset($values[$state[1]])) {
                $solution = $prevValue;
                break;
            }
            $prevValue = $state[1];
            $values[$state[1]] = true;
        }
        executeInstruction($instruction, $pointer, $state);
    }
    if($pointer['value'] < 0 || $pointer['value'] >= $numInstructions) {
        $inRange = false;
    }
}

echo 'Solution : ' . $solution . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;

function executeInstruction($instruction, &$pointer, &$state) {
    $state[$pointer['register']] = $pointer['value'];
    $opcode = $instruction['operator'];
    switch ($opcode) {
        case 'addr':
            $value1 = $state[$instruction['value1']];
            $value2 = $state[$instruction['value2']];
            $result = $value1 + $value2;
            break;
        case 'addi':
            $value1 = $state[$instruction['value1']];
            $value2 = $instruction['value2'];
            $result = $value1 + $value2;
            break;
        case 'mulr':
            $value1 = $state[$instruction['value1']];
            $value2 = $state[$instruction['value2']];
            $result = $value1 * $value2;
            break;
        case 'muli':
            $value1 = $state[$instruction['value1']];
            $value2 = $instruction['value2'];
            $result = $value1 * $value2;
            break;
        case 'banr':
            $value1 = $state[$instruction['value1']];
            $value2 = $state[$instruction['value2']];
            $result = $value1 & $value2;
            break;
        case 'bani':
            $value1 = $state[$instruction['value1']];
            $value2 = $instruction['value2'];
            $result = $value1 & $value2;
            break;
        case 'borr':
            $value1 = $state[$instruction['value1']];
            $value2 = $state[$instruction['value2']];
            $result = $value1 | $value2;
            break;
        case 'bori':
            $value1 = $state[$instruction['value1']];
            $value2 = $instruction['value2'];
            $result = $value1 | $value2;
            break;
        case 'setr':
            $result = $state[$instruction['value1']];
            break;
        case 'seti':
            $result = $instruction['value1'];
            break;
        case 'gtir':
            $value1 = $instruction['value1'];
            $value2 = $state[$instruction['value2']];
            $result = $value1 > $value2 ? 1 : 0;
            break;
        case 'gtri':
            $value1 = $state[$instruction['value1']];
            $value2 = $instruction['value2'];
            $result = $value1 > $value2 ? 1 : 0;
            break;
        case 'gtrr':
            $value1 = $state[$instruction['value1']];
            $value2 = $state[$instruction['value2']];
            $result = $value1 > $value2 ? 1 : 0;
            break;
        case 'eqir':
            $value1 = $instruction['value1'];
            $value2 = $state[$instruction['value2']];
            $result = $value1 == $value2 ? 1 : 0;
            break;
        case 'eqri':
            $value1 = $state[$instruction['value1']];
            $value2 = $instruction['value2'];
            $result = $value1 == $value2 ? 1 : 0;
            break;
        case 'eqrr':
            $value1 = $state[$instruction['value1']];
            $value2 = $state[$instruction['value2']];
            $result = $value1 == $value2 ? 1 : 0;
            break;
    }
    $state[$instruction['destiny']] = $result;
    $pointer['value'] = $state[$pointer['register']] + 1;
}

function findPattern($instructions) {
    $pattern = [
        ['seti', 0, 3, 5],
        ['addi', 5, 1, 4],
        ['muli', 4, 256, 4],
        ['gtrr', 4, 2, 4],
        ['addr', 4, 3, 3],
        ['addi', 3, 1, 3],
        ['seti', 25, 3, 3],
        ['addi', 5, 1, 5],
        ['seti', 17, 5, 3],
        ['setr', 5, 5, 2],
        ['seti', 7, 6, 3]
    ];
    
    foreach($instructions as $key => $instruction) {
        if($instruction['operator'] != $pattern[0][0]) {
            continue;
        }
        
        $find = true;
        
        foreach($pattern as $offset => $expected) {
            if(!isset($instructions[$key + $offset])) {
                $find = false;
                break;
            }
            
            $current = $instructions[$key + $offset];
            
            if(
                $current['operator'] != $expected[0] ||
                $current['value1'] != $expected[1] ||
                $current['value2'] != $expected[2] ||
                $current['destiny'] != $expected[3]
            ) {
                $find = false;
                break;
            }
        }
        
        if($find) {
            return $key;
        }
    }
    
    return null;
}