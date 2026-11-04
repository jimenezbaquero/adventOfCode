<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$targetGeneration = 50000000000;

$solution = 0;

$isRule = false;
$state = [];
$rules = [];
$states = [];

foreach ($data as $line) {
    if (empty($line)) {
        $isRule = true;
        continue;
    }
    
    if (!$isRule) {
        $initialState = str_replace('initial state: ', '', $line);
        
        foreach (str_split($initialState) as $position => $value) {
            if ($value == '#') {
                $state[$position] = '#';
            }
        }
    } else {
        $aux = explode(' => ', $line);
        $rules[$aux[0]] = $aux[1];
    }
}

$generation = 0;

$min = min(array_keys($state));
$max = max(array_keys($state));

$cycleFound = false;

while ($generation < $targetGeneration) {
    
    $pattern = '';
    
    for ($i = $min; $i <= $max; $i++) {
        $pattern .= isset($state[$i]) ? '#' : '.';
    }
    
    if (!$cycleFound && isset($states[$pattern])) {
        
        $previous = $states[$pattern];
        
        $cycleLength = $generation - $previous['generation'];
        $displacement = $min - $previous['min'];
        
        $remaining = $targetGeneration - $generation;
        $cycles = intdiv($remaining, $cycleLength);
        
        if ($cycles > 0) {
            $totalDisplacement = $cycles * $displacement;
            
            $stateAux = [];
            
            foreach ($state as $position => $value) {
                $stateAux[$position + $totalDisplacement] = '#';
            }
            
            $state = $stateAux;
            
            $min += $totalDisplacement;
            $max += $totalDisplacement;
            
            $generation += $cycles * $cycleLength;
        }
        
        $cycleFound = true;
        
    } elseif (!$cycleFound) {
        
        $states[$pattern] = [
            'generation' => $generation,
            'min' => $min,
        ];
    }
    
    if ($generation >= $targetGeneration) {
        break;
    }
    
    $stateAux = [];
    
    $newMin = null;
    $newMax = null;
    
    for ($i = $min - 2; $i <= $max + 2; $i++) {
        
        $block = '';
        
        for ($j = $i - 2; $j <= $i + 2; $j++) {
            $block .= isset($state[$j]) ? '#' : '.';
        }
        
        $result = $rules[$block] ?? '.';
        
        if ($result == '#') {
            $stateAux[$i] = '#';
            
            if ($newMin === null || $i < $newMin) {
                $newMin = $i;
            }
            
            if ($newMax === null || $i > $newMax) {
                $newMax = $i;
            }
        }
    }
    
    $state = $stateAux;
    
    if ($newMin !== null) {
        $min = $newMin;
        $max = $newMax;
    }
    
    $generation++;
    
}

$solution = array_sum(array_keys($state));

echo 'Solution: ' . $solution . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;