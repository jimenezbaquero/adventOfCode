<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$total = 0;


foreach($data as $line){
    $amounts = explode('x',$line);
    $large = $amounts[0];
    $width = $amounts[1];
    $height = $amounts[2];

    $maxSide = max($large,$width,$height);
    $amounts = removeFirst($amounts,$maxSide);

    $total += 2*$amounts[0]+2*$amounts[1]+$large*$width*$height;
}

echo 'Solution: '.$total.PHP_EOL;
echo 'Tiempo: '.number_format(microtime(true) - $start,2);

