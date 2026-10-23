<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$programs = [];

foreach ($data as $line) {
    $aux = explode(" <-> ", $line);
    $programs[$aux[0]] = explode(', ', $aux[1]);
}
$groups = 0;
$visits = [];

foreach ($programs as $node => $program) {
    if(!isset($visits[$node])) {
        calculateGroup($node,$programs, $visits);
        $groups++;
    }
}

echo 'Solution : '.$groups.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start, 2).PHP_EOL;
echo 'Memory used: '.number_format(memory_get_peak_usage() / 1024 / 1024, 2).' MB'.PHP_EOL;
echo 'Memory reserved: '.number_format(memory_get_peak_usage(true) / 1024 / 1024, 2).' MB'.PHP_EOL;

function calculateGroup($node, $programs, &$visited) {
    $visited[$node] = true;
    foreach ($programs[$node] as $program) {
        if(isset($visited[$program])) {
            continue;
        }
        calculateGroup($program, $programs, $visited);
    }
}
