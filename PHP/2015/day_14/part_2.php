<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$runners = [];
$positions = [];
$scores = [];
$time = 2503;

foreach ($data as $line) {
    $aux = explode(" ", str_replace('.','',$line));
    $runners[$aux[0]] = [
        'velocity' => $aux[3],
        'duration' => $aux[6],
        'rest' => $aux[13],
    ];
}

foreach ($runners as $index => $runner) {
    $positions[$index] = 0;
    $scores[$index] = 0;
}

for($i = 1; $i <= $time; $i++) {
    $maxPosition = 0;
    $leaders = [];
    foreach ($positions as $index => $position) {
        $positions[$index] += calculateDistance($runners[$index], $i);
        if($maxPosition < $positions[$index]) {
            $maxPosition = $positions[$index];
            $leaders = [$index];
        }else if ($maxPosition == $positions[$index]) {
            $leaders[] =$index;
        }
    }
    
    foreach ($leaders as $leader) {
        $scores[$leader]++;
    }
}

$maxScore = max($scores);

echo 'Solution: ' . $maxScore . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2);

function calculateDistance($runner, $time) {
    $cicleTime = $runner['duration'] + $runner['rest'];
    $timeLeft = $time % $cicleTime;
    if($timeLeft == 0) {
        return 0;
    }
    if($timeLeft <= $runner['duration']) {
        return $runner['velocity'];
    }
    return 0;
}