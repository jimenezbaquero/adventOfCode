<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');


$floor = 0;

foreach (str_split($data[0]) as $char) {
    if ($char == '(') {
        $floor++;
    } else {
        $floor--;
    }
}

echo 'Solution: '.$floor.PHP_EOL;
echo 'Tiempo: '.number_format(microtime(true) - $start,2);

