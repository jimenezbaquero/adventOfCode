<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$solution = 0;
$registers=[];
$instructions = [];
foreach ($data as $line) {
    $aux = explode(" ", str_replace('if ', "", $line));
    $instructions[] = [
        'register' => $aux[0],
        'operator' => $aux[1],
        'value' => intval($aux[2]),
        'cond1' => $aux[3],
        'condition' => $aux[4],
        'cond2' => intval($aux[5]),
    ];
}

foreach ($instructions as $instruction) {
    if(!isset($registers[$instruction['register']])) {
        $registers[$instruction['register']] = 0;
    }
    if(!isset($registers[$instruction['cond1']])) {
        $registers[$instruction['cond1']] = 0;
    }
    
    if(executeInstruction($instruction['condition'], $registers[$instruction['cond1']], $instruction['cond2'])){
        if($instruction['operator'] == 'inc'){
            $registers[$instruction['register']] += $instruction['value'];
        }else{
            $registers[$instruction['register']] -= $instruction['value'];
        }
    }
    $max = max($registers);
    if($max > $solution) {
        $solution = $max;
    }
}



echo 'Solution : '.$solution.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start, 2).PHP_EOL;
echo 'Memory used: '.number_format(memory_get_peak_usage() / 1024 / 1024, 2).' MB'.PHP_EOL;
echo 'Memory reserved: '.number_format(memory_get_peak_usage(true) / 1024 / 1024, 2).' MB'.PHP_EOL;

function executeInstruction($condition, $cond1, $cond2){
    switch ($condition) {
        case '>':
            return $cond1 > $cond2;
        case '<':
            return $cond1 < $cond2;
        case '<=':
            return $cond1 <= $cond2;
        case '>=':
            return $cond1 >= $cond2;
        case '==':
            return $cond1 == $cond2;
        case '!=':
            return $cond1 != $cond2;
        default:
            return false;
    }
}