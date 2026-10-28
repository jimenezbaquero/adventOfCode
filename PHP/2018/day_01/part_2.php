<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$frequency = 0;
$frequencies = [];

while(true){
    foreach ($data as $line) {
        $frequency += intval($line);
        if(isset($frequencies[$frequency])){
            break 2;
        }
        $frequencies[$frequency] = true;
    }
}

echo 'Solution : '.$frequency.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start, 2).PHP_EOL;
echo 'Memory used: '.number_format(memory_get_peak_usage() / 1024 / 1024, 2).' MB'.PHP_EOL;
echo 'Memory reserved: '.number_format(memory_get_peak_usage(true) / 1024 / 1024, 2).' MB'.PHP_EOL;

