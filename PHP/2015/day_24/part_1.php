<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$packages = [];
$weight = 0;

foreach ($data as $line) {
    $packages[] = intval($line);
    $weight += intval($line);
}

$objectiveWeight = $weight / 3;

rsort($packages);

$minPackages = calculateMinPackages($packages, $objectiveWeight);
$maxPackages = calculateMaxPackages($packages, $objectiveWeight);
$maxPackages = min($maxPackages, count($packages) - 2 * $minPackages);

$combinations = [];
$correctCombinations = [];

for ($i = $minPackages; $i <= $minPackages + 2; $i++) {
    $combinations[$i] = calculateVariations($packages, $i, $objectiveWeight);
    foreach ($combinations[$i] as $combination) {
        $newCombinations = [];
        $newPackages = array_diff($packages, $combination);
        rsort($newPackages);
        $newMinPackages = calculateMinPackages($newPackages, $objectiveWeight);
        $newMaxPackages = calculateMaxPackages($newPackages, $objectiveWeight);
        $newMaxPackages = min($newMaxPackages, count($packages) - 2 * $newMinPackages);
        
        for ($j = $newMinPackages; $j <= $newMinPackages + 2; $j++) {
            if(!isset($newCombinations[$j])){
                $newCombinations[$j] = calculateVariations($newPackages, $j, $objectiveWeight);
            }
            if(!empty($newCombinations[$j])){
                $correctCombinations[] = $combination;
                continue 2;
            }
        }
    }
}

$minorCombinations = calculateQE($correctCombinations);

usort($minorCombinations, function ($a, $b) {
    return $a['qe'] <=> $b['qe'];
});


$solution = $minorCombinations[0]['qe'];

echo 'Solution: ' . $solution . PHP_EOL;
echo 'Tiempo: ' . number_format(microtime(true) - $start, 2);

function calculateQE($combinations) {
    $newCombinations = [];
    foreach ($combinations as $combination) {
        $newCombinations[] = [
            'packages' => $combination,
            'qe' => array_product($combination)
        ];
    }
    usort($newCombinations, function ($a, $b) {
        return $a['qe'] <=> $b['qe'];
    });
    return $newCombinations;
}

function calculateMinPackages($packages, $objectiveWeight) {
    $minPackages = 0;
    $weight = 0;
    rsort($packages);
    foreach ($packages as $package) {
        $minPackages++;
        $weight += floatval($package);
        if ($weight >= $objectiveWeight) {
            return $minPackages;
        }
    }
    return $minPackages;
}

function calculateMaxPackages($packages, $objectiveWeight) {
    $maxPackages = 0;
    $weight = 0;
    sort($packages);
    foreach ($packages as $package) {
        $maxPackages++;
        $weight += floatval($package);
        if ($weight >= $objectiveWeight) {
            return $maxPackages;
        }
    }
    return $maxPackages;
}


function calculateVariations($array, $size, $objective, $startIndex = 0, $current = [], $currentWeight = 0) {
    if (count($current) == $size) {
        if ($currentWeight == $objective) {
            return [$current];
        } else {
            return [];
        }
    }
    
    $combinations = [];
    
    for ($i = $startIndex; $i < count($array); $i++) {
        $newCurrent = $current;
        $newCurrent[] = $array[$i];
        $newCurrentWeight = $currentWeight + intval($array[$i]);
        
        if ($newCurrentWeight <= $objective) {
            $results = calculateVariations($array, $size, $objective, $i + 1, $newCurrent, $newCurrentWeight);
            $combinations = array_merge($combinations, $results);
        }
    }
    
    return $combinations;
}