<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$solution = 'No solution';
$sons = [];
$towers = [];

foreach ($data as $line) {
    $line = str_replace(['(',')'],'',$line);
    if(str_contains($line,'->')){
        $aux = explode(' -> ', $line);
        $stringTower = $aux[0];
        $stringSons = $aux[1];
        $hasSon = true;
    }else{
        $stringTower = $line;
        $hasSon = false;
    }
    $auxTower = explode (' ', $stringTower);
    $tower = $auxTower[0];
    $towers[$tower] = intval($auxTower[1]);
    if($hasSon) {
        $auxSon = explode(', ', $stringSons);
        foreach ($auxSon as $son) {
            $sons[$son] = $tower;
        }
    }
}

foreach ($towers as $tower => $weight) {
    if(!isset($sons[$tower])){
        $solution = $tower;
        break;
    }
}

echo 'Solution : '.$solution.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start, 2).PHP_EOL;
echo 'Memory used: '.number_format(memory_get_peak_usage() / 1024 / 1024, 2).' MB'.PHP_EOL;
echo 'Memory reserved: '.number_format(memory_get_peak_usage(true) / 1024 / 1024, 2).' MB'.PHP_EOL;

