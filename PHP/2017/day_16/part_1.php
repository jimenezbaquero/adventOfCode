<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
$programs = ['a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p'];

//$data = loadFile('test.txt');
//$programs = ['a','b','c','d','e'];



$solution = 0;
$size = count($programs);
$moves = [];

foreach (explode(',',$data[0]) as $instruction) {
    $operation = substr($instruction, 0, 1);
    $rest = substr($instruction, 1);
    switch ($operation) {
        case 's':
            $move = [
                'operation' => 'spin',
                'amount' => intval($rest)
            ];
            break;
        case 'x':
            $aux = explode('/',$rest);
            $move = [
                'operation' => 'exchange',
                'position1' => intval($aux[0]),
                'position2' => intval($aux[1])
            ];
            break;
        case 'p':
            $aux = explode('/',$rest);
            $move = [
                'operation' => 'partner',
                'program1' => $aux[0],
                'program2' => $aux[1]
            ];
            break;
    }
    $moves[] = $move;
}

foreach ($moves as $move) {
    switch ($move['operation']) {
        case 'spin':
            $aux = [];
            for ($i = 0; $i < $size; $i++) {
                $aux[($i + $move['amount']) % $size] = $programs[$i];
            }
            $programs = $aux;
            break;
        case 'exchange':
            $aux = $programs[$move['position1']];
            $programs[$move['position1']] = $programs[$move['position2']];
            $programs[$move['position2']] = $aux;
            break;
        case 'partner':
            $aux1 = array_search($move['program1'], $programs);
            $aux2 = array_search($move['program2'], $programs);
            $aux = $programs[$aux1];
            $programs[$aux1] = $programs[$aux2];
            $programs[$aux2] = $aux;
            break;
    }
}

ksort($programs);
$solution = implode('', $programs);

echo 'Solution : ' . $solution . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;


