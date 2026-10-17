<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$total = 0;

foreach ($data as $line) {
    $aux = explode(' ',$line);
    while (!empty($aux)) {
        $word = array_shift($aux);
        if(existCopy($word,$aux)) {
            continue 2;
        }
    }
    $total++;
}


echo 'Solution : '.$total.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start, 2).PHP_EOL;
echo 'Memory used: '.number_format(memory_get_peak_usage() / 1024 / 1024, 2).' MB'.PHP_EOL;
echo 'Memory reserved: '.number_format(memory_get_peak_usage(true) / 1024 / 1024, 2).' MB'.PHP_EOL;

function existCopy($word,$arrayWords) {
    $length = strlen($word);
    while (!empty($arrayWords)) {
        $aux = array_shift($arrayWords);
        if (strlen($aux) !== $length) {
            continue;
        }
        foreach (str_split($word) as $letter) {
            $position = strpos($aux, $letter);
            if ($position === false) {
                continue 2;
            }
            $aux = substr_replace($aux, '*', $position, 1);
        }
        return true;
    }
    return false;
}