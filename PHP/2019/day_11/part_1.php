<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$solution = 0;
$outputs = [];
$directions = ['U', 'D', 'L', 'R'];
$program = [];
$grid = [];
$program = array_map(function($v) {return intval($v);},explode(",", $data[0]));
$posProgram = 0;
$base = 0;
$pos = [
    'col' => 0,
    'row' => 0
];

$direction = 'U';
$outputCell = true;
while(true) {
    if(!isset($grid[$pos['col']][$pos['row']])) {
        $grid[$pos['col']][$pos['row']] = ['color' => 'B', 'painted' => false];
    }
    $cell = $grid[$pos['col']][$pos['row']];
    $input = $cell['color'] == 'B' ? 0 : 1;
    $aux1 = executeProgram($program, $posProgram, $base, $input);
    
    if($aux1['error']){
        echo 'Se ha producido un error'.PHP_EOL;
        break;
    }
    
    if($aux1['finish']){
        break;
    }
    
    $output = $aux1['output'];
    
    if ($output == 0) {
        $painted = $cell['painted'] || $cell['color'] !== 'B';
        $grid[$pos['col']][$pos['row']] = ['color' => 'B', 'painted' => $painted];
    } else {
        $painted = $cell['painted'] || $cell['color'] !== 'W';
        $grid[$pos['col']][$pos['row']] = ['color' => 'W', 'painted' => $painted];
    }
    if($cell['painted'] != $painted){
        $solution++;
    }
    
    $aux2 = executeProgram($program, $posProgram, $base, $input);
    
    if($aux2['error']){
        echo 'Se ha producido un error'.PHP_EOL;
        break;
    }
    
    if($aux2['finish']){
        break;
    }
    
    $output = $aux2['output'];

    if($output == 0){
        switch($direction){
            case 'U':
                $direction = 'L';
                $pos['col'] = $pos['col'] - 1;
                break;
            case 'L':
                $direction = 'D';
                $pos['row'] = $pos['row'] + 1;
                break;
            case 'D':
                $direction = 'R';
                $pos['col'] = $pos['col'] + 1;
                break;
            case 'R':
                $direction = 'U';
                $pos['row'] = $pos['row'] - 1;
                break;
        }
    }else {
        switch ($direction) {
            case 'U':
                $direction = 'R';
                $pos['col'] = $pos['col'] + 1;
                break;
            case 'L':
                $direction = 'U';
                $pos['row'] = $pos['row'] - 1;
                break;
            case 'D':
                $direction = 'L';
                $pos['col'] = $pos['col'] - 1;
                break;
            case 'R':
                $direction = 'D';
                $pos['row'] = $pos['row'] + 1;
                break;
        }
    }
}


echo 'Solution : '.$solution.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start, 2).PHP_EOL;
echo 'Memory used: '.number_format(memory_get_peak_usage() / 1024 / 1024, 2).' MB'.PHP_EOL;
echo 'Memory reserved: '.number_format(memory_get_peak_usage(true) / 1024 / 1024, 2).' MB'.PHP_EOL;

function calculateValue($values, $pos, $base, $program){
    $aux = $values[$pos]['value'];
    if($values[$pos]['mode'] === 2){
        $aux += $base;
    }
    if($values[$pos]['mode'] === 1){
        $value = $aux;
    }else{
        if(isset($program[$aux])) {
            $value = $program[$aux];
        }else{
            $value = 0;
        }
    }
    return $value;
}

function executeProgram(&$program, &$pos, &$base, $input){
    $numParams = [
        1 => 3,
        2 => 3,
        3 => 1,
        4 => 1,
        5 => 2,
        6 => 2,
        7 => 3,
        8 => 3,
        9 => 1,
        99 => 0
    ];
    
    while(true){
        $values = [];
        $instruction = $program[$pos];
        $opcode = $instruction % 100;
        if(!isset($numParams[$opcode])) {
            return ['error' => true];
        }
        
        if($opcode === 99){
            return [
                'error' => false,
                'finish' =>true,
            ];
        }
        $numValue = 1;
        
        for($i = 0; $i < $numParams[$opcode] ; $i++){
            $dividend = 10 ** ($i + 2);
            $mode = intdiv($instruction, $dividend) % 10;
            $values[$numValue] = [
                'value' => $program[$pos + $numValue],
                'mode' => $mode
            ];
            $numValue++;
        }
        
        switch($opcode){
            case 1:
            case 2:
            case 5:
            case 6:
            case 7:
            case 8:
                $value1 = calculateValue($values, 1, $base, $program);
                $value2 = calculateValue($values, 2, $base, $program);
                
                if($opcode != 5 && $opcode != 6){
                    $destiny = $values[3]['value'];
                    if($values[3]['mode'] === 2){
                        $destiny += $base;
                    }
                }
                
                if($opcode == 5) {
                    if ($value1 == 0) {
                        $advance = 3;
                    } else {
                        $advance = $value2 - $pos;
                    }
                }else if($opcode == 6){
                    if($value1 != 0){
                        $advance = 3;
                    }else {
                        $advance = $value2 - $pos;
                    }
                }else {
                    $advance = 4;
                }
                break;
            case 3:
                $destiny = $values[1]['value'];
                if($values[1]['mode'] === 2){
                    $destiny += $base;
                }
                $advance = 2;
                break;
            case 4:
            case 9:
                $value1 = calculateValue($values, 1, $base, $program);
                $advance = 2;
                break;
            
            default:
                return [];
        }
        
        switch($opcode){
            case 1:
                $program[$destiny] = $value1 + $value2;
                break;
            case 2:
                $program[$destiny] = $value1 * $value2;
                break;
            case 3:
                $program[$destiny] = $input;
                break;
            case 4:
                $output = $value1;
                $pos += $advance;
                return [
                    'error' => false,
                    'finish' => false,
                    'output' => $output
                ];
            case 7:
                $program[$destiny] = $value1 < $value2 ? 1 : 0;
                break;
            case 8:
                $program[$destiny] = $value1 === $value2 ? 1 : 0;
                break;
            case 9:
                $base += $value1;
        }
        $pos += $advance;
    }
}