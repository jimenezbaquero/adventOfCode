<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$instruction = $data[0];

for ($i = 1; $i <= 40; $i++) {
    $instruction = calculateCode($instruction);
}

echo 'Solution: ' . strlen($instruction) . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2);

function calculateCode($instruction) {
    $response = '';
    $count = 0;
    $last = null;
    for ($i = 0; $i < strlen($instruction); $i++) {
        if (is_null($last)) {
            $last = $instruction[$i];
            $count++;
        } else if ($last !== $instruction[$i]) {
            $response .= $count . $last;
            $count = 1;
            $last = $instruction[$i];
        } else {
            $count++;
        }
    }
    
    $response .= $count . $last;
    
    return $response;
}