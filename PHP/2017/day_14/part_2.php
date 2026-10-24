<?php

$start = microtime(true);

require_once('../../functions.php');

$data = 'hxtvlmkl';
//$data = 'flqrgnkx';

$solution = 0;
$hashes = [];

for ($i = 0; $i < 128; $i++) {
    $hash = knotHash($data.'-'.$i);
    $binary = '';
    
    foreach (str_split($hash) as $char) {
        $binary .= str_pad(
            decbin(hexdec($char)),
            4,
            '0',
            STR_PAD_LEFT
        );
    }
    $hashes[] = str_split($binary);
}

$nodes = [];
foreach ($hashes as $row =>$hash) {
    foreach ($hash as $column => $char) {
        if($char == 0){
            continue;
        }
        $nodes[$row.','.$column] = calculateNeighbours($row, $column, $hashes);
    }
}

$groups = 0;
$visits = [];

foreach ($nodes as $node => $neighbours) {
    if(!isset($visits[$node])) {
        calculateGroup($node,$nodes, $visits);
        $groups++;
    }
}

$solution = $groups;


echo 'Solution : ' . $solution . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;


function knotHash($input) {
    
    $solution = '';
    $size = 256;
    
    $chars = str_split($input);
    $aux = [];
    
    foreach ($chars as $char) {
        $aux [] = ord($char);
    }
    
    $movements = array_merge($aux, [
        17,
        31,
        73,
        47,
        23
    ]);
    
    $skip = 0;
    $pos = 0;
    $grid = [];
    for ($i = 0; $i < $size; $i++) {
        $grid[] = $i;
    }
    
    for ($j = 0; $j < 64; $j++) {
        foreach ($movements as $char) {
            $aux = $grid;
            for ($i = 0; $i < $char; $i++) {
                $aux[($pos + $char - 1 - $i) % $size] = $grid[($pos + $i) % $size];
            }
            
            $grid = $aux;
            $pos = ($pos + $char + $skip) % $size;
            $skip++;
        }
    }
    
    $result = 0;
    for ($i = 0; $i < $size; $i++) {
        $result ^= $grid[$i];
        if ($i % 16 == 15) {
            $solution .= str_pad(dechex($result), 2, '0', STR_PAD_LEFT);
            $result = 0;
        }
    }
    return $solution;
}

function calculateNeighbours($row, $column, $hashes) {
    $directions = [[1,0],[-1,0],[0, 1],[0,-1]];
    $aux = [];
    foreach ($directions as $direction) {
        if (!isset($hashes[$row + $direction[0]][$column + $direction[1]])) {
            continue;
        }
        if($hashes[$row + $direction[0]][$column + $direction[1]] == 1) {
            $aux[] = ($row + $direction[0]).','.($column + $direction[1]);
        }
    }
    return $aux;
}

function calculateGroup($node, $neightbors, &$visited) {
    $visited[$node] = true;
    foreach ($neightbors[$node] as $neightbor) {
        if(isset($visited[$neightbor])) {
            continue;
        }
        calculateGroup($neightbor, $neightbors, $visited);
    }
}