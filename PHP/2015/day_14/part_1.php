<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$runners = [];
$time = 2503;

foreach ($data as $line) {
    $aux = explode(" ", str_replace('.','',$line));
    $runners[$aux[0]] = [
        'velocity' => $aux[3],
        'duration' => $aux[6],
        'rest' => $aux[13],
    ];
}

$maxDistance = 0;
foreach ($runners as $index => $runner) {
    $distance = calculateDistance($runner, $time);
    if($distance > $maxDistance) {
        $maxDistance = $distance;
    }
}

echo 'Solution: ' . $maxDistance . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2);

function calculateDistance($runner, $time) {
    $cicleTime = $runner['duration'] + $runner['rest'];
    $numberCicles = floor($time/$cicleTime);
    $timeLeft = $time % $cicleTime;
    if($timeLeft > $runner['duration']) {
        $timeLeft = $runner['duration'];
    }
    return ($numberCicles * $runner['duration'] + $timeLeft) * $runner['velocity'];
}