<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$total = 0;

foreach ($data as $line) {
    $line = preg_replace('/\s+/', ' ', $line);
    $aux = explode(' ', $line);
    sort($aux, SORT_NUMERIC);
    $length = count($aux);
    $total += intval($aux[$length - 1]) - intval($aux[0]);
}


echo 'Solution : '.$total.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start, 2).PHP_EOL;
echo 'Memory used: '.number_format(memory_get_peak_usage() / 1024 / 1024, 2).' MB'.PHP_EOL;
echo 'Memory reserved: '.number_format(memory_get_peak_usage(true) / 1024 / 1024, 2).' MB'.PHP_EOL;

