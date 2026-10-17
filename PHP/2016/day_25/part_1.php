<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$instructions = [];



foreach ($data as $line) {
    $aux = explode(" ", $line);
    $originalInstructions[] = [
        'operation' => $aux[0],
        'value' => $aux[1],
        ...(isset($aux[2]) ? ['value2' => $aux[2]] : [])
    ];
}

$solution = 'No solution';
$i = 0;

while(true) {
    echo 'Probando para a = '.$i.PHP_EOL;
    $prevOutput = '';
    $sizeOutput = 0;
    $state = [
        'a' => $i,
        'b' => 0,
        'c' => 0,
        'd' => 0,
    ];
    
    $instructions = $originalInstructions;
    $instructionNumber = 0;
    $hasNextInstruction = true;
    $iteracion = 1;
    
    while ($hasNextInstruction) {
        $instruction = $instructions[$instructionNumber];
        $clockPattern = findClockPattern($instructions);
        
        if ($clockPattern !== false) {
            $j = 1;
            
            while (!isClockSignal($j + $clockPattern['increment'])) {
                $j++;
            }
            
            $solution = $j;
            break 2;
        }
        
        $product = findProduct($instructionNumber, $instructions, $state);
        if ($product !== false) {
            $value = $state[$product['source']] * $state[$product['outerCounter']];
            
            if ($product['operation'] == 'sum') {
                $state[$product['target']] += $value;
            } else {
                $state[$product['target']] -= $value;
            }
            
            $state[$product['counter']] = 0;
            $state[$product['outerCounter']] = 0;
            
            $instructionNumber += 6;
        } else {
            $pattern = findPattern($instructionNumber, $instructions, $state);
            if ($pattern !== false) {
                if ($pattern['operation'] == 'sum') {
                    $state[$pattern['target']] += $state[$pattern['counter']];
                } else {
                    $state[$pattern['target']] -= $state[$pattern['counter']];
                }
                
                $state[$pattern['counter']] = 0;
                $instructionNumber += 3;
            } else {
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
                        if (!isset($instructions[$instructionNumber + $value]) || $value == 0) {
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
                        } else {
                            if ($auxInstruction['operation'] == 'jnz') {
                                $instructions[$destinyInstruction]['operation'] = 'cpy';
                            } else {
                                $instructions[$destinyInstruction]['operation'] = 'jnz';
                            }
                        }
                        $instructionNumber++;
                        break;
                    case 'out':
                        $output = $state[$instruction['value']];
                        if (!in_array($output, [0, 1]) || $output == $prevOutput ) {
                            $i++;
                            break 2;
                        }
                        
                        $sizeOutput++;
                        $prevOutput  = $output;
                        $instructionNumber++;
                        
                        if($sizeOutput > 100) {
                            $solution = $i;
                            break 3;
                    }
                        break;
                }
                
                if ($instructionNumber < 0 || $instructionNumber >= count($instructions)) {
                    $hasNextInstruction = false;
                    $i++;
                }
            }
        }
    }
}

echo 'Solution: ' . $solution . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2).PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;

function findPattern($instructionNumber, $instructions, $state) {
    if (!isset($instructions[$instructionNumber + 2])) {
        return false;
    }
    
    $instruction1 = $instructions[$instructionNumber];
    $instruction2 = $instructions[$instructionNumber + 1];
    $instruction3 = $instructions[$instructionNumber + 2];
    
    if (!in_array($instruction1['operation'], ['inc', 'dec'])
        || !in_array($instruction2['operation'], ['inc', 'dec'])
        || $instruction3['operation'] != 'jnz') {
        return false;
    }
    
    if ($instruction3['value2'] != '-2') {
        return false;
    }
    
    $counter = $instruction3['value'];
    
    if (!isset($state[$counter]) || $state[$counter] <= 0) {
        return false;
    }
    
    if ($instruction1['operation'] == 'dec' && $instruction1['value'] == $counter) {
        $targetInstruction = $instruction2;
    } elseif ($instruction2['operation'] == 'dec' && $instruction2['value'] == $counter) {
        $targetInstruction = $instruction1;
    } else {
        return false;
    }
    
    if (!isset($state[$targetInstruction['value']])
        || $targetInstruction['value'] == $counter) {
        return false;
    }
    
    return [
        'operation' => $targetInstruction['operation'] == 'inc' ? 'sum' : 'minus',
        'target' => $targetInstruction['value'],
        'counter' => $counter,
    ];
}

function findProduct($instructionNumber, $instructions, $state) {
    if (!isset($instructions[$instructionNumber + 5])) {
        return false;
    }
    
    $instruction1 = $instructions[$instructionNumber];
    $instruction2 = $instructions[$instructionNumber + 1];
    $instruction3 = $instructions[$instructionNumber + 2];
    $instruction4 = $instructions[$instructionNumber + 3];
    $instruction5 = $instructions[$instructionNumber + 4];
    $instruction6 = $instructions[$instructionNumber + 5];
    
    if ($instruction1['operation'] != 'cpy'
        || !in_array($instruction2['operation'], ['inc', 'dec'])
        || $instruction3['operation'] != 'dec'
        || $instruction4['operation'] != 'jnz'
        || $instruction5['operation'] != 'dec'
        || $instruction6['operation'] != 'jnz') {
        return false;
    }
    
    if ($instruction4['value2'] != '-2'
        || $instruction6['value2'] != '-5') {
        return false;
    }
    
    $source = $instruction1['value'];
    $target = $instruction2['value'];
    $counter = $instruction3['value'];
    $outerCounter = $instruction5['value'];
    
    if (!isset($state[$source], $state[$target], $state[$counter], $state[$outerCounter])) {
        return false;
    }
    
    if (count(array_unique([$source, $target, $counter, $outerCounter])) != 4) {
        return false;
    }
    
    if ($instruction1['value2'] != $counter) {
        return false;
    }
    
    if ($instruction4['value'] != $counter
        || $instruction6['value'] != $outerCounter) {
        return false;
    }
    
    // Comprobar que los contadores son positivos
    if ($state[$source] <= 0 || $state[$outerCounter] <= 0) {
        return false;
    }
    
    return [
        'target' => $target,
        'source' => $source,
        'counter' => $counter,
        'outerCounter' => $outerCounter,
        'operation' => $instruction2['operation'] == 'inc' ? 'sum' : 'minus',
    ];
    
}


function findClockPattern($instructions) {
    
    $matches = function($index, $operation, $value = null, $value2 = null) use ($instructions) {
        
        if (!isset($instructions[$index])) {
            return false;
        }
        
        $instruction = $instructions[$index];
        
        if ($instruction['operation'] != $operation) {
            return false;
        }
        
        if ($value !== null && $instruction['value'] != $value) {
            return false;
        }
        
        if ($value2 !== null && $instruction['value2'] != $value2) {
            return false;
        }
        
        return true;
    };
    
    foreach ($instructions as $index => $instruction) {
        
        if ($instruction['operation'] != 'out') {
            continue;
        }
        
        $start = $index - 17;
        $initial = $start - 10;
        
        if ($initial < 0) {
            continue;
        }
        
        if (!$matches($initial, 'cpy', 'a', 'd')
            || !$matches($initial + 1, 'cpy')
            || !$matches($initial + 2, 'cpy')
            || !is_numeric($instructions[$initial + 1]['value'])
            || !is_numeric($instructions[$initial + 2]['value'])
            || !$matches($initial + 3, 'inc', 'd')
            || !$matches($initial + 4, 'dec', 'b')
            || !$matches($initial + 5, 'jnz', 'b', '-2')
            || !$matches($initial + 6, 'dec', 'c')
            || !$matches($initial + 7, 'jnz', 'c', '-5')
            || !$matches($initial + 8, 'cpy', 'd', 'a')
            || !$matches($initial + 9, 'jnz', '0', '0')) {
            continue;
        }
        
        if (!$matches($start, 'cpy', 'a', 'b')
            || !$matches($start + 1, 'cpy', '0', 'a')
            || !$matches($start + 2, 'cpy', '2', 'c')
            || !$matches($start + 3, 'jnz', 'b', '2')
            || !$matches($start + 4, 'jnz', '1', '6')
            || !$matches($start + 5, 'dec', 'b')
            || !$matches($start + 6, 'dec', 'c')
            || !$matches($start + 7, 'jnz', 'c', '-4')
            || !$matches($start + 8, 'inc', 'a')
            || !$matches($start + 9, 'jnz', '1', '-7')
            || !$matches($start + 10, 'cpy', '2', 'b')
            || !$matches($start + 11, 'jnz', 'c', '2')
            || !$matches($start + 12, 'jnz', '1', '4')
            || !$matches($start + 13, 'dec', 'b')
            || !$matches($start + 14, 'dec', 'c')
            || !$matches($start + 15, 'jnz', '1', '-4')
            || !$matches($start + 16, 'jnz', '0', '0')) {
            continue;
        }
        
        if (!$matches($index, 'out', 'b')
            || !$matches($index + 1, 'jnz', 'a', '-19')
            || !$matches($index + 2, 'jnz', '1', '-21')) {
            continue;
        }
        
        return [
            'increment' => intval($instructions[$initial + 1]['value'])
                * intval($instructions[$initial + 2]['value']),
            'initialIndex' => $initial,
            'divisionIndex' => $start,
            'outputIndex' => $index,
        ];
    }
    
    return false;
}

function isClockSignal($value): bool {
    
    $binary = decbin($value);
    
    if (strlen($binary) % 2 != 0) {
        return false;
    }
    
    for ($i = 1; $i < strlen($binary); $i++) {
        if ($binary[$i] == $binary[$i - 1]) {
            return false;
        }
    }
    
    return true;
}
