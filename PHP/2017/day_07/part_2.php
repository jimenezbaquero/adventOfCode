<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$solution = 'No solution';
$sons = [];
$towers = [];
$allSons = [];

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
            $sons[$tower][]= $son;
            $allSons[] = $son;
        }
    }
}

$father = array_values(array_diff(array_keys($towers),$allSons))[0];

$weights = [];
calculateWeights($father,$towers, $sons, $weights);

$found = false;
while(!$found) {
    $weightsSonsFather = [];
    foreach ($sons[$father] as $son) {
        $weightsSonsFather[$son] = $weights[$son];
    }
    $aux = calculateWrong($weightsSonsFather);
    if(!is_null($aux)){
        $father = $aux[0];
        $weight = $aux[1];
        $ballanceWeight = $aux[2];
        $difference = abs($weight - $ballanceWeight);
        $correctWeight = $ballanceWeight > $weight ? $towers[$father] + $difference : $towers[$father] - $difference;
    }else{
        $found = true;
    }
}

$solution = $correctWeight;

echo 'Solution : '.$solution.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start, 2).PHP_EOL;
echo 'Memory used: '.number_format(memory_get_peak_usage() / 1024 / 1024, 2).' MB'.PHP_EOL;
echo 'Memory reserved: '.number_format(memory_get_peak_usage(true) / 1024 / 1024, 2).' MB'.PHP_EOL;




function calculateWeights($program, $programs, $sons, &$weights) {
    if(isset($weights[$program])){
        return $weights[$program];
    }
    $total = $programs[$program];
    if(!isset($sons[$program])){
        $weights[$program] = $total;
        return $total;
    }else {
        foreach ($sons[$program] as $son) {
            $total += calculateWeights($son, $programs, $sons, $weights);
        }
        $weights[$program] = $total;
        return $total;
    }
}

function calculateWrong($weights) {
    if(count(array_unique($weights)) == 1){
        return null;
    }
    foreach ($weights as $key1 => $weight1) {
        foreach ($weights as $key2 => $weight2) {
            if($key1 == $key2){
                continue;
            }
            if($weight1 != $weight2){
                foreach ($weights as $key3 => $weight3) {
                    if($key1 == $key3 || $key2 == $key3){
                        continue;
                    }
                    if($weight1 == $weight3){
                        return [$key2, $weight2, $weight1];
                    }else{
                        return [$key1, $weight1, $weight2];
                    }
                }
            }
        }
        
    }
    return null;
}