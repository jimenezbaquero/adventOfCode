<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$instructions = [];

$state = [
    'a' => 12,
    'b' => 0,
    'c' => 0,
    'd' => 0,
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
    }else {
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
            }
            
            if ($instructionNumber < 0 || $instructionNumber >= count($instructions)) {
                $hasNextInstruction = false;
            }
        }
    }
}
$solution = $state['a'];

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
    
    // Comprobar operaciones
    if ($instruction1['operation'] != 'cpy'
        || !in_array($instruction2['operation'], ['inc', 'dec'])
        || $instruction3['operation'] != 'dec'
        || $instruction4['operation'] != 'jnz'
        || $instruction5['operation'] != 'dec'
        || $instruction6['operation'] != 'jnz') {
        return false;
    }
    
    // Comprobar saltos
    if ($instruction4['value2'] != '-2'
        || $instruction6['value2'] != '-5') {
        return false;
    }
    
    $source = $instruction1['value'];
    $target = $instruction2['value'];
    $counter = $instruction3['value'];
    $outerCounter = $instruction5['value'];
    
    // Comprobar registros
    if (!isset($state[$source], $state[$target], $state[$counter], $state[$outerCounter])) {
        return false;
    }
    
    if (count(array_unique([$source, $target, $counter, $outerCounter])) != 4) {
        return false;
    }
    
    // Comprobar que cpy copia en el contador interior
    if ($instruction1['value2'] != $counter) {
        return false;
    }
    
    // Comprobar saltos sobre los registros correctos
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