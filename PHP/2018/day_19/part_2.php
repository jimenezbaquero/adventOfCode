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
    1,
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

while ($inRange) {
    if(findPattern($instructions, $pointer)){
        $state = [
            $state[0] + sumDivisors($state[2]),
            $state[2] + 1,
            $state[2],
            1,
            $state[2] + 1,
            15
        ];
        $pointer['value'] = 16;
    }else {
        $instruction = $instructions[$pointer['value']];
        executeInstruction($instruction, $pointer, $state);
    }
    if($pointer['value'] < 0 || $pointer['value'] >= $numInstructions) {
        $inRange = false;
    }
}

$solution = $state[0];

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

function findPattern($instructions, $pointer) {
    $instructionNumber = $pointer['value'];

    $pattern = [
        -1  => ['seti', 1, 5, 4],
        0   => ['mulr', 1, 4, 3],
        1   => ['eqrr', 3, 2, 3],
        2   => ['addr', 3, 5, 5],
        3   => ['addi', 5, 1, 5],
        4   => ['addr', 1, 0, 0],
        5   => ['addi', 4, 1, 4],
        6   => ['gtrr', 4, 2, 3],
        7   => ['addr', 5, 3, 5],
        8   => ['seti', 2, 4, 5],
        9   => ['addi', 1, 1, 1],
        10  => ['gtrr', 1, 2, 3],
        11  => ['addr', 3, 5, 5],
        12  => ['seti', 1, 9, 5],
    ];

    foreach ($pattern as $offset => $expected) {
        $instruction = $instructions[$instructionNumber + $offset] ?? null;

        if (
            $instruction === null ||
            $instruction['operator'] != $expected[0] ||
            $instruction['value1'] != $expected[1] ||
            $instruction['value2'] != $expected[2] ||
            $instruction['destiny'] != $expected[3]
        ) {
            return false;
        }
    }

    return true;
}

function sumDivisors($number) {
    $sum = 0;
    $limit = sqrt($number);
    for ($i = 1; $i <= $limit; $i++) {
        if ($number % $i == 0) {
            $sum += $i;
            $divisor = $number / $i;

            if($i != $divisor) {
                $sum += $divisor;
            }
        }
    }
return $sum;
}



