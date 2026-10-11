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

$objectiveWeight = $weight / 4;

rsort($packages);

$minPackages = calculateMinPackages($packages, $objectiveWeight);
$maxPackages = calculateMaxPackages($packages, $objectiveWeight);
$maxPackages = min($maxPackages, count($packages) - 3 * $minPackages);

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
        $newMaxPackages = min($newMaxPackages, count($newPackages) - 3 * $newMinPackages);
        
        for ($j = $newMinPackages; $j <= $newMinPackages + 2; $j++) {
            if(!isset($newCombinations[$j])){
                $newCombinations[$j] = calculateVariations($newPackages, $j, $objectiveWeight);
            }
            if(!empty($newCombinations[$j])){
                foreach ($newCombinations[$j] as $combination2) {
                    $newCombinations2 = [];
                    $newPackages2 = array_diff($newPackages, $combination2);
                    rsort($newPackages2);
                    $newMinPackages2 = calculateMinPackages($newPackages2, $objectiveWeight);
                    $newMaxPackages2 = calculateMaxPackages($newPackages2, $objectiveWeight);
                    $newMaxPackages2 = min($newMaxPackages2, count($newPackages) - 3 * $newMinPackages2);
                    
                    for ($l = $newMinPackages2; $l <= $newMinPackages2 + 2; $l++) {
                        if(!isset($newCombinations2[$l])){
                            $newCombinations2[$l] = calculateVariations($newPackages2, $l, $objectiveWeight);
                        }
                        if(!empty($newCombinations2[$l])){
                            $correctCombinations[] = $combination;
                            continue 2;
                        }
                    }
                }
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