<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$total = 0;
$lights = [];
for ($i = 0; $i < 1000; $i++) {
    for ($j = 0; $j < 1000; $j++) {
        $lights[$i][$j] = 0;
    }
}

foreach ($data as $instruction) {
    $instruction = str_replace('turn ', '', $instruction);
    $instruction = str_replace('through ', '', $instruction);
    
    $aux = explode(' ', $instruction);
    
    $operation = $aux[0];
    $initialCell = explode(',', $aux[1]);
    $finalCell = explode(',', $aux[2]);
    
    for ($i = $initialCell[0]; $i <= $finalCell[0]; $i++) {
        for ($j = $initialCell[1]; $j <= $finalCell[1]; $j++) {
            if ($operation == 'on') {
                $lights[$i][$j]++;
            } else if ($operation == 'off') {
                $lights[$i][$j]--;
                if ($lights[$i][$j] < 0) {
                    $lights[$i][$j] = 0;
                }
            } else {
                $lights[$i][$j] += 2;
            }
        }
    }
}

foreach ($lights as $row) {
    $total += array_sum($row);
}

echo 'Solution: ' . $total . PHP_EOL;
echo 'Tiempo: ' . number_format(microtime(true) - $start, 2);



