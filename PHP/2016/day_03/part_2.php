<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$triangles = [];
$aux = [];

foreach ($data as $line) {
    $aux[] = preg_split('/\s+/', trim($line));
}

$column1 = [];
$column2 = [];
$column3 = [];

foreach ($aux as $key => $line) {
    $column1[] = intval($line[0]);
    $column2[] = intval($line[1]);
    $column3[] = intval($line[2]);
    
    if((($key + 1) % 3 ) == 0 ){
      $triangles[] = $column1;
      $triangles[] = $column2;
      $triangles[] = $column3;
        $column1 = [];
        $column2 = [];
        $column3 = [];
    }
}

$validTriangles = 0;

foreach ($triangles as $triangle) {
    $side0 = $triangle[0];
    $side1 = $triangle[1];
    $side2 = $triangle[2];
    if($side0 + $side1 > $side2 && $side0 + $side2 > $side1 && $side1 + $side2 > $side0) {
        $validTriangles++;
    }
}

echo 'Solution: '.$validTriangles.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start,2);

