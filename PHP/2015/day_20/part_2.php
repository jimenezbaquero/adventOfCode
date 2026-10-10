<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$gifts = $data[0];
$maxHouse = floor($gifts / 11);
$houses = [];
$minHouse = PHP_INT_MAX;

$find = false;
$elf = 1;

while(!$find){
    $min = min(factorial($elf), $maxHouse);
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


$elf = 1;
$min = PHP_INT_MAX;

while ($elf <= $maxHouse) {
    $aux = 0;
    $sumElves = $elf * ($elf + 1) / 2;
    $checkHouses = $sumElves >= $maxHouse;
    for($i = 1; $i <= 50; $i++) {
        $house = $elf*$i;
        if($house > $maxHouse){
            break;
        }
        $houses[$house] = ($houses[$house] ?? 0) + $elf * 11;
        if ($checkHouses && $houses[$house] >= $gifts) {
            if ($house < $min) {
                $min = $house;
            }
        }
    }
    $elf++;
}

echo 'Solution: ' . $min . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2);

