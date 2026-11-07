<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$solution = 0;

$aux = [];
$numLine = 0;
foreach ($data as $line) {
    $numLine++;
    if(empty($line)){
        if(count($aux) == 3) {
            if (checkOptions($aux)) {
                $solution++;
            }
        }
        $aux = [];
        $numLine = 0;
    }else{
        switch ($numLine) {
            case 1:
                $aux['before'] = str_replace(['Before: [', ']'], '', $line);
                break;
            case 2:
                $aux['instruction'] = $line;
                break;
            case 3:
                $aux['after'] = str_replace(['After:  [', ']'], '', $line);
                break;
        }
    }
}

echo 'Solution : ' . $solution . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;

function checkOptions($aux) {
    
    $opcodes = [
        'addr',
        'addi',
        'mulr',
        'muli',
        'banr',
        'bani',
        'borr',
        'bori',
        'setr',
        'seti',
        'gtir',
        'gtri',
        'gtrr',
        'eqir',
        'eqri',
        'eqrr',
    ];
    
    $beforeState = array_map(function($v) {return intval($v);},explode(', ', $aux['before']));
    $instruction = array_map(function($v) {return intval($v);},explode(' ', $aux['instruction']));
    $afterState = array_map(function($v) {return intval($v);},explode(', ', $aux['after']));
    $destiny = $instruction[3];
    $posibles = 0;
    
    foreach ($opcodes as $opcode) {
        $result = null;
        $isValidRegister1 = $instruction[1] <=3 && $instruction[1] >= 0;
        $isValidRegister2 = $instruction[2] <=3 && $instruction[2] >= 0;
        switch ($opcode) {
            case 'addr':
                if($isValidRegister1 && $isValidRegister2) {
                    $value1 = $beforeState[$instruction[1]];
                    $value2 = $beforeState[$instruction[2]];
                    $result = $value1 + $value2;
                }
                break;
            case 'addi':
                if($isValidRegister1){
                    $value1 = $beforeState[$instruction[1]];
                    $value2 = $instruction[2];
                    $result = $value1 + $value2;
                }
                break;
            case 'mulr':
                if($isValidRegister1 && $isValidRegister2) {
                    $value1 = $beforeState[$instruction[1]];
                    $value2 = $beforeState[$instruction[2]];
                    $result = $value1 * $value2;
                }
                break;
            case 'muli':
                if($isValidRegister1){
                    $value1 = $beforeState[$instruction[1]];
                    $value2 = $instruction[2];
                    $result = $value1 * $value2;
                }
                break;
            case 'banr':
                if($isValidRegister1 && $isValidRegister2) {
                    $value1 = $beforeState[$instruction[1]];
                    $value2 = $beforeState[$instruction[2]];
                    $result = $value1 & $value2;
                }
                break;
            case 'bani':
                if($isValidRegister1){
                    $value1 = $beforeState[$instruction[1]];
                    $value2 = $instruction[2];
                    $result = $value1 & $value2;
                }
                break;
            case 'borr':
                if($isValidRegister1 && $isValidRegister2) {
                    $value1 = $beforeState[$instruction[1]];
                    $value2 = $beforeState[$instruction[2]];
                    $result = $value1 | $value2;
                }
                break;
            case 'bori':
                if($isValidRegister1){
                    $value1 = $beforeState[$instruction[1]];
                    $value2 = $instruction[2];
                    $result = $value1 | $value2;
                }
                break;
            case 'setr':
                if($isValidRegister1){
                    $result = $beforeState[$instruction[1]];
                }
                break;
            case 'seti':
                $result = $instruction[1];
                break;
            case 'gtir':
                if($isValidRegister2){
                    $value1 = $instruction[1];
                    $value2 = $beforeState[$instruction[2]];
                    $result = $value1 > $value2 ? 1 : 0;
                }
                break;
            case 'gtri':
                if($isValidRegister1){
                    $value1 = $beforeState[$instruction[1]];
                    $value2 = $instruction[2];
                    $result = $value1 > $value2 ? 1 : 0;
                }
                break;
            case 'gtrr':
                if($isValidRegister1 && $isValidRegister2) {
                    $value1 = $beforeState[$instruction[1]];
                    $value2 = $beforeState[$instruction[2]];
                    $result = $value1 > $value2 ? 1 : 0;
                }
                break;
            case 'eqir':
                if($isValidRegister2){
                    $value1 = $instruction[1];
                    $value2 = $beforeState[$instruction[2]];
                    $result = $value1 == $value2 ? 1 : 0;
                }
                break;
            case 'eqri':
                if($isValidRegister1){
                    $value1 = $beforeState[$instruction[1]];
                    $value2 = $instruction[2];
                    $result = $value1 == $value2 ? 1 : 0;
                }
                break;
            case 'eqrr':
                if($isValidRegister1 && $isValidRegister2) {
                    $value1 = $beforeState[$instruction[1]];
                    $value2 = $beforeState[$instruction[2]];
                    $result = $value1 == $value2 ? 1 : 0;
                    
                }
                break;
        }
        if(!is_null($result) && passOperation($beforeState, $afterState, $result, $destiny)){
            $posibles++;
        }
    }
    if($posibles >= 3) {
        return true;
    }
    return false;
}

function passOperation($beforeState, $afterState, $result, $destiny){
    for($i = 0; $i < 4; $i++) {
        if($i == $destiny) {
            if($result != $afterState[$i]) {
                return false;
            }
        }else if($beforeState[$i] != $afterState[$i]){
            return false;
        }
    }
    return true;
}