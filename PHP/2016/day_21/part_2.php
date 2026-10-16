<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$sword = 'fbgdceah';

$instructions = [];

foreach ($data as $line) {
    $aux = explode(' ', $line);
    
    switch ($aux[0]) {
        case 'swap':
        case 'move':
            $instructions[] = [
                'operation' => $aux[0],
                'type' => $aux[1],
                'value1' => $aux[5],
                'value2' => $aux[2],
            ];
            break;
        
        case 'reverse':
            $instructions[] = [
                'operation' => $aux[0],
                'type' => $aux[1],
                'value1' => $aux[2],
                'value2' => $aux[4],
            ];
            break;
        
        case 'rotate':
            switch ($aux[1]) {
                case 'left':
                case 'right':
                    $instructions[] = [
                        'operation' => $aux[0],
                        'type' => $aux[1] == 'left' ? 'right' : 'left',
                        'value' => $aux[2],
                    ];
                    break;
                
                case 'based':
                    $instructions[] = [
                        'operation' => $aux[0],
                        'type' => $aux[1],
                        'value' => $aux[6],
                    ];
                    break;
            }
            break;
    }
}

$instructions = array_reverse($instructions);

foreach ($instructions as $instruction) {
    $aux = str_split($sword);
    $newSword = [];
    switch ($instruction['operation']) {
        case 'swap':
            if ($instruction['type'] == 'position'){
                foreach ($aux as $key => $char) {
                    if($key == $instruction['value1']){
                        $newSword[$instruction['value2']] = $char;
                    }else if($key == $instruction['value2']){
                        $newSword[$instruction['value1']] = $char;
                    }else{
                        $newSword[$key] = $char;
                    }
                }
            } else{
                $value1 = strpos($sword,$instruction['value1']);
                $value2 = strpos($sword,$instruction['value2']);
                foreach ($aux as $key => $char) {
                    if($key == $value1){
                        $newSword[$value2] = $char;
                    }else if($key == $value2){
                        $newSword[$value1] = $char;
                    }else{
                        $newSword[$key] = $char;
                    }
                }
            }
            break;
        case 'move':
            foreach ($aux as $key => $char) {
                if($instruction['value1'] < $instruction['value2']){
                    if($key == $instruction['value1']){
                        $newSword[$instruction['value2']] = $char;
                    }
                    else if($key < $instruction['value1'] || $key > $instruction['value2']){
                        $newSword[$key] = $char;
                    }else if($key > $instruction['value1']){
                        $newSword[$key - 1] = $char;
                    }
                }else{
                    if($key == $instruction['value1']){
                        $newSword[$instruction['value2']] = $char;
                    }
                    else if($key < $instruction['value2'] || $key > $instruction['value1']){
                        $newSword[$key] = $char;
                    }else if($key >= $instruction['value2']){
                        $newSword[$key + 1] = $char;
                    }
                }
            }
            break;
        case 'reverse':
            foreach ($aux as $key => $char) {
                if($key < $instruction['value1'] || $key > $instruction['value2']){
                    $newSword[$key] = $char;
                }else{
                    $newSword[$instruction['value2'] - ($key - $instruction['value1']) ] = $char;
                }
            }
            break;
        case 'rotate':
            if ($instruction['type'] == 'based') {
                $length = strlen($sword);
                
                for ($offset = 0; $offset < $length; $offset++) {
                    $candidate = substr($sword, $offset) . substr($sword, 0, $offset);
                    
                    $position = strpos($candidate, $instruction['value']);
                    $steps = $position + 1;
                    
                    if ($position >= 4) {
                        $steps++;
                    }
                    
                    $steps %= $length;
                    
                    if ($steps == 0) {
                        $result = $candidate;
                    } else {
                        $result = substr($candidate, -$steps) . substr($candidate, 0, $length - $steps);
                    }
                    
                    if ($result == $sword) {
                        $newSword = str_split($candidate);
                        break;
                    }
                }
            } else {
                if ($instruction['type'] == 'right') {
                    $direction = 1;
                } else {
                    $direction = -1;
                }
                
                $steps = $instruction['value'] % strlen($sword);
                
                foreach ($aux as $key => $char) {
                    $newSword[($key + strlen($sword) + ($direction * $steps)) % strlen($sword)] = $char;
                }
            }
            break;
        
    }
    ksort($newSword);
    $sword = implode('', $newSword);
}

echo 'Solution : '.$sword. PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;
