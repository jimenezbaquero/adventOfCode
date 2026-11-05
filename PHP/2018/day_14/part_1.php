<?php

$start = microtime(true);

require_once('../../functions.php');

$data = 320851;

$elf1 = 0;
$elf2 = 1;

$recipes = [3, 7];
$size = 2;
while ($size <= ($data + 10)){
    $value1 = $recipes[$elf1];
    $value2 = $recipes[$elf2];
    $sum = $value1 + $value2;
    $digit1 = $sum % 10;
    $digit2 = ($sum - $digit1) / 10;
    if($digit2 != 0){
        $recipes[] = $digit2;
    }
    $recipes[] = $digit1;
    $size = count($recipes);
    $elf1 = ($elf1 + $value1 + 1) % $size;
    $elf2 = ($elf2 + $value2 + 1) % $size;
}

$solution = implode('', array_slice($recipes, $data, 10));


echo 'Solution : ' . $solution . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;
