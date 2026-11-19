<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$pixels = $data[0];
$solution = 0;

$wide = 25;
$tall = 6;
$layers = [];

while($pixels != '') {
    $auxLayer = substr($pixels, 0, $wide * $tall);
    $pixels = substr($pixels, $wide * $tall);
    $aux = [];
    for($i = 0; $i < $tall; $i++) {
        $aux[] = str_split(substr($auxLayer, $wide * $i, $wide));
    }
    $layers[] = $aux;
}

$image = [];
for($i = 0; $i < $tall; $i++) {
    for($j = 0; $j < $wide; $j++) {
        $aux = null;
        foreach($layers as $layer) {
            $pixel = $layer[$i][$j];
            if (is_null($aux) || $aux == '2') {
                $aux = $pixel;
            }
        }
        $image[$i][$j] = $aux == '0' ? ' ' : ($aux == '1' ? '#' : ' ');
    }
}

paintFull($image);

echo 'Solution : '.$solution.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start, 2).PHP_EOL;
echo 'Memory used: '.number_format(memory_get_peak_usage() / 1024 / 1024, 2).' MB'.PHP_EOL;
echo 'Memory reserved: '.number_format(memory_get_peak_usage(true) / 1024 / 1024, 2).' MB'.PHP_EOL;
