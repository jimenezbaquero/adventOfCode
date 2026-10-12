<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$instructions = [];

foreach ($data as $line) {
    $aux = explode(" ", $line);
    $operation = $aux[0];
    switch ($operation) {
        case('rect'):
            $aux2 = explode("x", $aux[1]);
            $width = $aux2[0];
            $height = $aux2[1];
            $instructions[] = [
                'operation' => $operation,
                'width' => intval($width),
                'height' => intval($height)
            ];
            break;
        case('rotate');
            $item = $aux[1];
            $itemNumber = substr($aux[2], strpos($aux[2], "=") + 1);
            $amount = $aux[4];
            $instructions[] = [
                'operation' => $operation,
                'item' => $item,
                'itemNumber' => intval($itemNumber),
                'amount' => intval($amount)
            ];
            break;
        default:
            echo 'Unknown operation: ' . $operation . PHP_EOL;
    }
}

$screen = [];
$screenWidth = 50;
$screenHeight = 6;

for ($row = 0; $row < $screenHeight; $row++) {
    for ($column = 0; $column < $screenWidth; $column++) {
        $screen[$row][$column] = '.';
    }
}

foreach ($instructions as $instruction) {
    $copyScreen = $screen;
    switch ($instruction['operation']) {
        case('rect'):
            for ($row = 0; $row < $instruction['height']; $row++) {
                for ($column = 0 ;$column < $instruction['width']; $column++) {
                    $copyScreen[$row][$column] = '#';
                }
            }
            break;
        case('rotate'):
            if ($instruction['item'] == 'row') {
                for ($i = 0; $i < $screenWidth; $i++) {
                    $copyScreen[$instruction['itemNumber']][(($i + $instruction['amount']) % $screenWidth)] = $screen[$instruction['itemNumber']][$i];
                }
            } else if ($instruction['item'] == 'column') {
                for ($j = 0; $j < $screenHeight; $j++) {
                    $copyScreen[(($j + $instruction['amount']) % $screenHeight)][$instruction['itemNumber']] = $screen[$j][$instruction['itemNumber']];
                }
            }
            break;
        default:
            echo 'Unknown operation: ' . PHP_EOL;
    }
    $screen = $copyScreen;
}

$total = 0;
foreach ($screen as $row) {
    foreach ($row as $cell) {
        if ($cell == '#') {
            $total++;
        }
    }
}

echo 'Solution: ' .$total. PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2);

