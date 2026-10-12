<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$triangles = [];

foreach ($data as $line) {
    $triangles[] = preg_split('/\s+/', trim($line));
}

$validTriangles = 0;

foreach ($triangles as $triangle) {
    $side0 = intval($triangle[0]);
    $side1 = intval($triangle[1]);
    $side2 = intval($triangle[2]);
    if($side0 + $side1 > $side2 && $side0 + $side2 > $side1 && $side1 + $side2 > $side0) {
        $validTriangles++;
    }
}

echo 'Solution: '.$validTriangles.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start,2);

