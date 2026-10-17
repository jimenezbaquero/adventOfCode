<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$solution = 0;
$captcha = $data[0];
$prevValue = substr($captcha, -1, 1);

for($i = 0; $i < strlen($captcha); $i++){
    if ($captcha[$i] == $prevValue){
        $solution += $prevValue;
    }
    $prevValue = $captcha[$i];
}

echo 'Solution : '.$solution.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start, 2).PHP_EOL;
echo 'Memory used: '.number_format(memory_get_peak_usage() / 1024 / 1024, 2).' MB'.PHP_EOL;
echo 'Memory reserved: '.number_format(memory_get_peak_usage(true) / 1024 / 1024, 2).' MB'.PHP_EOL;

