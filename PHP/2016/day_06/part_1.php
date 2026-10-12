<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$columns = [];
$code = '';

foreach ($data as $line) {
    foreach (str_split($line) as $key => $letter) {
        $columns[$key][$letter] = isset($columns[$key][$letter]) ? $columns[$key][$letter] + 1 : 1;
    }
}

foreach ($columns as $column) {
    arsort($column);
    $code .= array_key_first($column);
}

echo 'Solution: '.$code.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start,2);

