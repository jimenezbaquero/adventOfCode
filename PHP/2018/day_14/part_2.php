<?php

$start = microtime(true);

require_once('../../functions.php');

$data = 320851;
$length = strlen((string)$data);

$elf1 = 0;
$elf2 = 1;

$recipes = '37';
$size = 2;

while (true) {
    $value1 = (int)$recipes[$elf1];
    $value2 = (int)$recipes[$elf2];
    
    $sum = $value1 + $value2;
    
    $digit1 = $sum % 10;
    $digit2 = ($sum - $digit1) / 10;
    
    if ($digit2 != 0) {
        $recipes .= $digit2;
        $size++;
        
        if ($size >= $length &&
            substr($recipes, -$length) === (string)$data) {
            $solution = $size - $length;
            break;
        }
    }
    
    $recipes .= $digit1;
    $size++;
    
    if ($size >= $length &&
        substr($recipes, -$length) === (string)$data) {
        $solution = $size - $length;
        break;
    }
    
    $elf1 = ($elf1 + $value1 + 1) % $size;
    $elf2 = ($elf2 + $value2 + 1) % $size;
}

echo 'Solution : ' . $solution . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;