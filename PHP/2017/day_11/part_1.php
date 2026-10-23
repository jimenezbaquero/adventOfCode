
<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$movements = explode(",", $data[0]);

$x = 0;
$y = 0;
$z = 0;

foreach ($movements as $movement) {
    switch ($movement) {
        case 'n':
            $y++;
            $z--;
            break;
        case 'ne':
            $x++;
            $z--;
            break;
        case 'se':
            $x++;
            $y--;
            break;
        case 's':
            $y--;
            $z++;
            break;
        case 'sw':
            $x--;
            $z++;
            break;
        case 'nw':
            $x--;
            $y++;
            break;
    }
}

$solution = max(abs($x), abs($y), abs($z));

echo 'Solution : '.$solution.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start, 2).PHP_EOL;
echo 'Memory used: '.number_format(memory_get_peak_usage() / 1024 / 1024, 2).' MB'.PHP_EOL;
echo 'Memory reserved: '.number_format(memory_get_peak_usage(true) / 1024 / 1024, 2).' MB'.PHP_EOL;
