<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$solution = 0;
$program = [];

$originalProgram = array_map(function($v) {return intval($v);},explode(",", $data[0]));

$find = false;
$noun = 0;
$verb = 0;
while(!$find) {
    $program = $originalProgram;
    $program[1] = $noun;
    $program[2] = $verb;
    
    $pos = 0;
    while ($program[$pos] != 99) {
        $value1 = $program[$program[$pos + 1]];
        $value2 = $program[$program[$pos + 2]];
        $destiny = $program[$pos + 3];
        
        if ($program[$pos] == 1) {
            $program[$destiny] = $value1 + $value2;
        } else {
            $program[$destiny] = $value1 * $value2;
        }
        $pos += 4;
    }
    if($program[0] == 19690720){
        $find = true;
    }else{
        $noun++;
        if($noun == 100){
            $noun = 0;
            $verb++;
        }
    }
}

$solution = 100 * $noun + $verb;

echo 'Solution : '.$solution.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start, 2).PHP_EOL;
echo 'Memory used: '.number_format(memory_get_peak_usage() / 1024 / 1024, 2).' MB'.PHP_EOL;
echo 'Memory reserved: '.number_format(memory_get_peak_usage(true) / 1024 / 1024, 2).' MB'.PHP_EOL;

