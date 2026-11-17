<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$solution = 0;
$program = [];
$instructions = [];
$program = array_map(function($v) {return intval($v);},explode(",", $data[0]));
$size = count($program);
$pos = 0;

while($pos < $size){
    $values = [];
    $instruction = $program[$pos];
    $opcode = $instruction % 100;
    $numValue = 1;
    for($i = 100; $i <= 10000; $i *= 10){
        $mode = intdiv($instruction, $i) % 10;
            $values[$numValue] = [
                'value' => $program[$pos + $numValue],
                'mode' => $mode
            ];
        $numValue++;
    }
    
    switch($opcode){
        case 1:
        case 2:
            $value1 = $values[1]['mode'] === 0 ? $program[$values[1]['value']] : $values[1]['value'];
            $value2 = $values[2]['mode'] === 0 ? $program[$values[2]['value']] : $values[2]['value'];
            $destiny = $values[3]['value'];
            $advance = 4;
            break;
        case 3:
            $destiny = $values[1]['value'];
            $advance = 2;
            break;
        case 4:
            $output = $values[1]['mode'] === 0 ? $program[$values[1]['value']] : $values[1]['value'];
            $advance = 2;
            break;
    }
    
    switch($opcode){
        case 1:
            $program[$destiny] = $value1 + $value2;
            break;
        case 2:
            $program[$destiny] = $value1 * $value2;
            break;
        case 3:
            $program[$destiny] = 1;
            break;
        case 4:
            $solution = $output;
            if($solution != 0){
               if($program[$pos + $advance] != 99){
                   $solution = 'Error de diagnóstico';
                   break 2;
               }
            }
            break;
        case 99:
            break 2;
    }
    $pos += $advance;
}

echo 'Solution : '.$solution.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start, 2).PHP_EOL;
echo 'Memory used: '.number_format(memory_get_peak_usage() / 1024 / 1024, 2).' MB'.PHP_EOL;
echo 'Memory reserved: '.number_format(memory_get_peak_usage(true) / 1024 / 1024, 2).' MB'.PHP_EOL;

