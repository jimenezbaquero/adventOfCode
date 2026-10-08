<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$total = 0;
$wires = [];
$instructions = [];
$wireNames = [];

//Creación de array de instrucciones

foreach ($data as $instruction) {
    $aux = explode(' -> ', $instruction);
    $left = explode(' ', $aux[0]);
    $operation = 'ASSIGN';
    if (strpos($instruction, 'AND') !== false) {
        $operation = 'AND';
    } else if (strpos($instruction, 'OR') !== false) {
        $operation = 'OR';
    } else if (strpos($instruction, 'NOT') !== false) {
        $operation = 'NOT';
    } else if (strpos($instruction, 'LSHIFT') !== false) {
        $operation = 'LSHIFT';
    } else if (strpos($instruction, 'RSHIFT') !== false) {
        $operation = 'RSHIFT';
    }

    $instructions[] = [
        'operation' => $operation,
        'done' => false,
        'wire' => $aux[1],
        'left' => $left,
    ];

    $wireNames[] = $aux[1];
}

//Procesado de todas las instrucciones marcando como realizadas las que ya se han realizado

$pending = true;

while ($pending) {
    $pending = false;
    foreach ($instructions as $key => $instruction) {
        if ($instruction['done']) {
            continue;
        }

        $wire = $instruction['wire'];
        $left = $instruction['left'];
        $operation = $instruction['operation'];

        switch ($operation) {
            case 'ASSIGN':
                if (is_numeric($left[0])) {
                    $wires[$wire] = (int)$left[0];
                } else if (!in_array($left[0], $wireNames)) {
                    $instructions[$key]['done'] = true;
                } else if (isset($wires[$left[0]])) {
                    $wires[$wire] = $wires[$left[0]];
                } else {
                    $pending = true;
                    continue 2;
                }
                break;
            case 'AND':
                if (!is_numeric($left[0]) && (!isset($wires[$left[0]]) || !isset($wires[$left[2]])) ||
                    (is_numeric($left[0]) && !isset($wires[$left[2]]))) {
                    $pending = true;
                    continue 2;
                }
                if (is_numeric($left[0])) {
                    $wires[$wire] = $left[0] & $wires[$left[2]];
                } else if (in_array($left[0], $wireNames) && in_array($left[2], $wireNames)) {
                    $wires[$wire] = $wires[$left[0]] & $wires[$left[2]];
                }

                break;
            case 'OR':
                if (!is_numeric($left[0]) && (!isset($wires[$left[0]]) || !isset($wires[$left[2]]))) {
                    $pending = true;
                    continue 2;
                }
                if (is_numeric($left[0])) {
                    $wires[$wire] = $left[0] | $wires[$left[2]];
                } else if (in_array($left[0], $wireNames) && in_array($left[2], $wireNames)) {
                    $wires[$wire] = $wires[$left[0]] | $wires[$left[2]];
                }
                break;
            case 'NOT':
                if (!isset($wires[$left[1]])) {
                    $pending = true;
                    continue 2;
                }
                if (in_array($left[1], $wireNames)) {
                    $wires[$wire] = ~$wires[$left[1]] & 0xFFFF;
                }
                break;
            case 'LSHIFT':
                if (!isset($wires[$left[0]])) {
                    $pending = true;
                    continue 2;
                }
                if (in_array($left[0], $wireNames)) {
                    $wires[$wire] = $wires[$left[0]] << $left[2];
                }
                break;
            case 'RSHIFT':
                if (!isset($wires[$left[0]])) {
                    $pending = true;
                    continue 2;
                }
                if (in_array($left[0], $wireNames)) {
                    $wires[$wire] = $wires[$left[0]] >> $left[2];
                }
                break;
        }
        $instructions[$key]['done'] = true;
    }
}

$total = $wires['a'];

echo 'Solution: ' . $total . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2);
