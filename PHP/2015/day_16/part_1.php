<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$solution = null;
$sues = [];

$message = [
    'children' => 3,
    'cats' => 7,
    'samoyeds' => 2,
    'pomeranians' => 3,
    'akitas' => 0,
    'vizslas' => 0,
    'goldfish' => 5,
    'trees' => 3,
    'cars' => 2,
    'perfumes' => 1,
];

foreach ($data as $line) {
    $aux = explode(" ", str_replace(':', '', $line));
    $number = $aux[1];
    $sues[$aux[1]] = [
        $aux[2] => intval($aux[3]),
        $aux[4] => intval($aux[5]),
        $aux[6] => intval($aux[7])
    ];
}

foreach ($sues as $key => $sue) {
    foreach ($message as $property => $value) {
        if (!isset($sue[$property])) {
            continue;
        }
        if ($sue[$property] !== $value) {
            echo $key.' se descarta al ser '.$property.' = '.$sue[$property].' y debería ser '.$value.PHP_EOL;
            continue 2;
        }
    }
    $solution = $key;
    break;
}

echo 'Solution: ' . $solution . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2);
