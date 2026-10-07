<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$total = 0;


foreach($data as $line){
    $minArea = 0;
    $amounts = explode('x',$line);
    $large = $amounts[0];
    $width = $amounts[1];
    $height = $amounts[2];

    $firstArea = $large * $width;
    $secondArea = $large * $height;
    $thirdArea = $height * $width;

    $total += 2 * ($firstArea + $secondArea + $thirdArea) + min($firstArea, $secondArea, $thirdArea);
}

echo 'Solution: '.$total.PHP_EOL;
echo 'Tiempo: '.number_format(microtime(true) - $start,2);
