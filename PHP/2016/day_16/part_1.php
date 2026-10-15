<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$code = intval($data[0]);

$length = 272;

$find = false;

while (!$find) {
    $code = generateDragonCurve($code);
    if(strlen($code) >= $length) {
        $find = true;
    }
}
echo 'codigo generado'.PHP_EOL;

$code = substr($code, 0, $length);

$sum = generateSum($code);

echo 'Solution : '.$sum.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start,2);


function generateDragonCurve($code) {
    $a = $code;
    $a = str_split($code);
    $b = array_reverse($a);
    
    $b = array_map(function($x) {return $x=='1'? '0':'1';},$b);
    
    return $code.'0'.implode($b);
}

function generateSum($code) {
    $aux = $code;
    $isValid = true;
    while($isValid && $aux != '') {
        if (strlen($aux) % 2 == 0) {
            $pairs = str_split($aux, 2);
            $aux = '';
            foreach ($pairs as $pair) {
                $values = str_split($pair);
                if ($values[0] == $values[1]) {
                    $aux .= '1';
                } else {
                    $aux .= '0';
                }
            }
        } else {
            $isValid = false;
        }
    }
    return $aux;
}