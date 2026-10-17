<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$solution = 0;
$captcha = $data[0];
$length = strlen($captcha);
$halfLength = intdiv($length, 2);

for($i = 0; $i < $halfLength; $i++){
    if ($captcha[$i] == $captcha[$i + $halfLength]){
        $solution += 2 * $captcha[$i];
    }
}

echo 'Solution : '.$solution.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start, 2).PHP_EOL;
echo 'Memory used: '.number_format(memory_get_peak_usage() / 1024 / 1024, 2).' MB'.PHP_EOL;
echo 'Memory reserved: '.number_format(memory_get_peak_usage(true) / 1024 / 1024, 2).' MB'.PHP_EOL;
