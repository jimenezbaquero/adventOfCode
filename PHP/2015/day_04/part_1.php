<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$number = 0;


while(true){
    $hash = md5($data[0] . $number);
    if(substr($hash,0,5) === '00000'){
        break;
    }
    $number++;
}

echo 'Solution: ' .$number.PHP_EOL;
echo 'Tiempo: '.number_format(microtime(true) - $start,2);

