<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$solution = 0;

$operations = [
    'addr' => range(0, 15),
    'addi' => range(0, 15),
    'mulr' => range(0, 15),
    'muli' => range(0, 15),
    'banr' => range(0, 15),
    'bani' => range(0, 15),
    'borr' => range(0, 15),
    'bori' => range(0, 15),
    'setr' => range(0, 15),
    'seti' => range(0, 15),
    'gtir' => range(0, 15),
    'gtri' => range(0, 15),
    'gtrr' => range(0, 15),
    'eqir' => range(0, 15),
    'eqri' => range(0, 15),
    'eqrr' => range(0, 15),
];

$aux = [];
$numLine = 0;
$instructions = [];
$isInstruction = false;
$prevLine = '';
foreach ($data as $line) {
    $numLine++;
    if (empty($line)) {
        if (empty($prevLine)) {
            $isInstruction = true;
        }
        if (count($aux) == 3) {
            checkOptions($aux, $operations);
        }
        $aux = [];
        $numLine = 0;
    } else if (!$isInstruction) {
        switch ($numLine) {
            case 1:
                $aux['before'] = str_replace([
                    'Before: [',
                    ']'
                ], '', $line);
                break;
            case 2:
                $aux['instruction'] = $line;
                break;
            case 3:
                $aux['after'] = str_replace([
                    'After:  [',
                    ']'
                ], '', $line);
                break;
        }
    } else {
        $instructions[] = array_map(function ($v) {
            return intval($v);
        }, explode(' ', $line));
    }
    $prevLine = $line;
}

$allFind = false;
$anyClean = true;
while (!$allFind && $anyClean) {
    $anyClean = false;
    uasort($operations, fn($a, $b) => count($a) <=> count($b));
    foreach ($operations as $operation => $indexs) {
        if (count($indexs) != 1) {
            continue;
        }
        $index = array_shift($indexs);
        foreach ($operations as $key => $aux) {
            if ($key == $operation) {
                continue;
            }
            if (isset($aux[$index])) {
                unset($operations[$key][$index]);
                $anyClean = true;
            }
        }
    }
    
    $allFind = true;
    foreach ($operations as $indexs) {
        if (count($indexs) != 1) {
            $allFind = false;
            break;
        }
    }
}

$aux = [];

foreach ($operations as $operation => $codes) {
    $aux[array_values($codes)[0]] = $operation;
}

$operations = $aux;

$state = [
    0,
    0,
    0,
    0
];

foreach ($instructions as $instruction) {
    executeInstruction($instruction, $operations, $state);
}

$solution = $state[0];

echo 'Solution : ' . $solution . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;

function checkOptions($aux, &$operations) {
    
    $opcodes = array_keys($operations);
    
    $beforeState = array_map(function ($v) {
        return intval($v);
    }, explode(', ', $aux['before']));
    $instruction = array_map(function ($v) {
        return intval($v);
    }, explode(' ', $aux['instruction']));
    $afterState = array_map(function ($v) {
        return intval($v);
    }, explode(', ', $aux['after']));
    $destiny = $instruction[3];
    
    foreach ($opcodes as $opcode) {
        $result = null;
        $isValidRegister1 = $instruction[1] <= 3 && $instruction[1] >= 0;
        $isValidRegister2 = $instruction[2] <= 3 && $instruction[2] >= 0;
        switch ($opcode) {
            case 'addr':
                if ($isValidRegister1 && $isValidRegister2) {
                    $value1 = $beforeState[$instruction[1]];
                    $value2 = $beforeState[$instruction[2]];
                    $result = $value1 + $value2;
                }
                break;
            case 'addi':
                if ($isValidRegister1) {
                    $value1 = $beforeState[$instruction[1]];
                    $value2 = $instruction[2];
                    $result = $value1 + $value2;
                }
                break;
            case 'mulr':
                if ($isValidRegister1 && $isValidRegister2) {
                    $value1 = $beforeState[$instruction[1]];
                    $value2 = $beforeState[$instruction[2]];
                    $result = $value1 * $value2;
                }
                break;
            case 'muli':
                if ($isValidRegister1) {
                    $value1 = $beforeState[$instruction[1]];
                    $value2 = $instruction[2];
                    $result = $value1 * $value2;
                }
                break;
            case 'banr':
                if ($isValidRegister1 && $isValidRegister2) {
                    $value1 = $beforeState[$instruction[1]];
                    $value2 = $beforeState[$instruction[2]];
                    $result = $value1 & $value2;
                }
                break;
            case 'bani':
                if ($isValidRegister1) {
                    $value1 = $beforeState[$instruction[1]];
                    $value2 = $instruction[2];
                    $result = $value1 & $value2;
                }
                break;
            case 'borr':
                if ($isValidRegister1 && $isValidRegister2) {
                    $value1 = $beforeState[$instruction[1]];
                    $value2 = $beforeState[$instruction[2]];
                    $result = $value1 | $value2;
                }
                break;
            case 'bori':
                if ($isValidRegister1) {
                    $value1 = $beforeState[$instruction[1]];
                    $value2 = $instruction[2];
                    $result = $value1 | $value2;
                }
                break;
            case 'setr':
                if ($isValidRegister1) {
                    $result = $beforeState[$instruction[1]];
                }
                break;
            case 'seti':
                $result = $instruction[1];
                break;
            case 'gtir':
                if ($isValidRegister2) {
                    $value1 = $instruction[1];
                    $value2 = $beforeState[$instruction[2]];
                    $result = $value1 > $value2 ? 1 : 0;
                }
                break;
            case 'gtri':
                if ($isValidRegister1) {
                    $value1 = $beforeState[$instruction[1]];
                    $value2 = $instruction[2];
                    $result = $value1 > $value2 ? 1 : 0;
                }
                break;
            case 'gtrr':
                if ($isValidRegister1 && $isValidRegister2) {
                    $value1 = $beforeState[$instruction[1]];
                    $value2 = $beforeState[$instruction[2]];
                    $result = $value1 > $value2 ? 1 : 0;
                }
                break;
            case 'eqir':
                if ($isValidRegister2) {
                    $value1 = $instruction[1];
                    $value2 = $beforeState[$instruction[2]];
                    $result = $value1 == $value2 ? 1 : 0;
                }
                break;
            case 'eqri':
                if ($isValidRegister1) {
                    $value1 = $beforeState[$instruction[1]];
                    $value2 = $instruction[2];
                    $result = $value1 == $value2 ? 1 : 0;
                }
                break;
            case 'eqrr':
                if ($isValidRegister1 && $isValidRegister2) {
                    $value1 = $beforeState[$instruction[1]];
                    $value2 = $beforeState[$instruction[2]];
                    $result = $value1 == $value2 ? 1 : 0;
                    
                }
                break;
        }
        if (is_null($result) || !passOperation($beforeState, $afterState, $result, $destiny)) {
            if (isset($operations[$opcode][$instruction[0]])) {
                unset($operations[$opcode][$instruction[0]]);
            }
        }
    }
}

function passOperation($beforeState, $afterState, $result, $destiny) {
    for ($i = 0; $i < 4; $i++) {
        if ($i == $destiny) {
            if ($result != $afterState[$i]) {
                return false;
            }
        } else if ($beforeState[$i] != $afterState[$i]) {
            return false;
        }
    }
    return true;
}

function executeInstruction($instruction, $operations, &$state) {
    $opcode = $operations[$instruction[0]];
    switch ($opcode) {
        case 'addr':
            $value1 = $state[$instruction[1]];
            $value2 = $state[$instruction[2]];
            $result = $value1 + $value2;
            break;
        case 'addi':
            $value1 = $state[$instruction[1]];
            $value2 = $instruction[2];
            $result = $value1 + $value2;
            break;
        case 'mulr':
            $value1 = $state[$instruction[1]];
            $value2 = $state[$instruction[2]];
            $result = $value1 * $value2;
            break;
        case 'muli':
            $value1 = $state[$instruction[1]];
            $value2 = $instruction[2];
            $result = $value1 * $value2;
            break;
        case 'banr':
            $value1 = $state[$instruction[1]];
            $value2 = $state[$instruction[2]];
            $result = $value1 & $value2;
            break;
        case 'bani':
            $value1 = $state[$instruction[1]];
            $value2 = $instruction[2];
            $result = $value1 & $value2;
            break;
        case 'borr':
            $value1 = $state[$instruction[1]];
            $value2 = $state[$instruction[2]];
            $result = $value1 | $value2;
            break;
        case 'bori':
            $value1 = $state[$instruction[1]];
            $value2 = $instruction[2];
            $result = $value1 | $value2;
            break;
        case 'setr':
            $result = $state[$instruction[1]];
            break;
        case 'seti':
            $result = $instruction[1];
            break;
        case 'gtir':
            $value1 = $instruction[1];
            $value2 = $state[$instruction[2]];
            $result = $value1 > $value2 ? 1 : 0;
            break;
        case 'gtri':
            $value1 = $state[$instruction[1]];
            $value2 = $instruction[2];
            $result = $value1 > $value2 ? 1 : 0;
            break;
        case 'gtrr':
            $value1 = $state[$instruction[1]];
            $value2 = $state[$instruction[2]];
            $result = $value1 > $value2 ? 1 : 0;
            break;
        case 'eqir':
            $value1 = $instruction[1];
            $value2 = $state[$instruction[2]];
            $result = $value1 == $value2 ? 1 : 0;
            break;
        case 'eqri':
            $value1 = $state[$instruction[1]];
            $value2 = $instruction[2];
            $result = $value1 == $value2 ? 1 : 0;
            break;
        case 'eqrr':
            $value1 = $state[$instruction[1]];
            $value2 = $state[$instruction[2]];
            $result = $value1 == $value2 ? 1 : 0;
            break;
    }
    $state[$instruction[3]] = $result;
}