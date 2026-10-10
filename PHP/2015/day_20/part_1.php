<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$gifts = $data[0];
$maxHouses = $gifts / 10;
$houses = [];
$elf = 1;

$minHouse = PHP_INT_MAX;

$find = false;

while(!$find){
    $min = min(factorial($elf), $maxHouses);
    $minGifts = 0;
    
    for ($i = 1; $i <= $min; $i++) {
        if ($min % $i == 0) {
            $minGifts += $i * 10;
        }
    }
    
    if($minGifts >= $gifts){
        $minHouse = factorial($elf -1); ;
        $maxHouse = $min;
        $find = true;
    }else{
        $elf++;
    }
}

$find = false;
$repeat = 0;
$maxRepeat = 10;

while(!$find){
    $min = $maxHouse - rand(100000,250000);
    $minGifts = 0;

    for ($i = 1; $i <= $min; $i++) {
        if ($min % $i == 0) {
            $minGifts += $i * 10;
        }
    }

    if($minGifts < $gifts){
        $repeat++;
        if($repeat > $maxRepeat){
            $find = true;
        }
    }else{
        $repeat = 0;
        $maxHouse = $min;
    }
}

$elf = 1;
$min = PHP_INT_MAX;

while ($elf <= $maxHouse) {
    $aux = 0;
    $sumElves = $elf * ($elf + 1) / 2;
    $checkHouses = $sumElves >= $maxHouse;
    for ($house = $elf; $house <= $maxHouse; $house += $elf) {
        if($house > $minHouse) {
            $houses[$house] = ($houses[$house] ?? 0) + $elf * 10;
            if ($checkHouses && $houses[$house] >= $gifts) {
                if ($house < $min) {
                    $min = $house;
                }
            }
        }
    }
    $elf++;
}

echo 'Solution: ' . $min . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2);

