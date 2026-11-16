<?php

$start = microtime(true);

$min = 372304;
$max = 847060;

$solution = 0;
$numbers = [];

for($number = $min; $number <= $max; $number++) {
    if(hasDouble($number) && isCrecient($number)) {
        $solution++;
    }
}

echo 'Solution : '.$solution.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start, 2).PHP_EOL;
echo 'Memory used: '.number_format(memory_get_peak_usage() / 1024 / 1024, 2).' MB'.PHP_EOL;
echo 'Memory reserved: '.number_format(memory_get_peak_usage(true) / 1024 / 1024, 2).' MB'.PHP_EOL;

function hasDouble($number) {
    for($i = 0; $i < 10; $i++) {
        if(str_contains($number ,"{$i}{$i}")) {
            return true;
        }
    }
    return false;
}

function isCrecient($number) {
    $aux = str_split((string)$number);
    for($i = 0; $i < 5; $i++) {
        if($aux[$i + 1] < $aux[$i]) {
            return false;
        }
    }
    return true;
}