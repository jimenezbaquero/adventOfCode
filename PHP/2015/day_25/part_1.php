<?php
$start = microtime(true);

require_once('../../functions.php');

$codes = [];
$code = 20151125;
$row = 1;
$column = 1;

$calculate = false;
while (!$calculate) {
    if ($row - 1 == 0) {
        $row = $column + 1;
        $column = 1;
    } else {
        $row--;
        $column++;
    }
    
    $newCode = ($code * 252533) % 33554393;
    $code = $newCode;
    
    if($row == 2981 && $column == 3075) {
        $calculate = true;
    }
}

echo 'Solution: '.$code.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start,2).PHP_EOL;

$start = microtime(true);
$row = 2981;
$column = 3075;

$d = $row + $column - 1;
$n = ($d * ($d - 1)) / 2 + $column - 1;

$solution = bcmod(
    bcmul('20151125', bcpowmod('252533', (string) $n, '33554393'), 0),
    '33554393'
);

echo 'Solution: '.$code.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start,2);