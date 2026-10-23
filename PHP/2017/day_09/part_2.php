<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$solution = 0;

$instruction = $data[0];
$posCancel = strpos($instruction , '!');

while($posCancel !== false){
    $instruction = substr_replace($instruction,'',$posCancel, 2);
    $posCancel = strpos($instruction , '!');
}

$openPos = strpos($instruction , '<');
$closePos = strpos($instruction , '>');

while($openPos !== false && $closePos !== false && $openPos < $closePos){
    $solution += $closePos - $openPos - 1;
    $instruction = substr($instruction, $closePos + 1);
    $openPos = strpos($instruction , '<');
    $closePos = strpos($instruction , '>');
}


echo 'Solution : '.$solution.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start, 2).PHP_EOL;
echo 'Memory used: '.number_format(memory_get_peak_usage() / 1024 / 1024, 2).' MB'.PHP_EOL;
echo 'Memory reserved: '.number_format(memory_get_peak_usage(true) / 1024 / 1024, 2).' MB'.PHP_EOL;

