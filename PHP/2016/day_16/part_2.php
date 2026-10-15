<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$code = $data[0];

$targetLength = 35651584;

$steps = 0;
$aux = $targetLength;

while ($aux % 2 == 0) {
    $steps++;
    $aux = $aux / 2;
}

$solutionLength = $aux;

$sum = generateSum($code, $steps, $solutionLength);

echo 'Solution : '.$sum.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start,2);


function generateSum($code, $steps, $solutionLength) {
    $size = 2 ** $steps;
    $aux = $code;
    $aux2 = $code;
    $solution = '';
    while (true) {
        $newBlock = '0'.strtr(strrev($aux),'10','01');
        $aux .= $newBlock;
        $aux2 .= $newBlock;
        if(strlen($aux2) >= $size) {
            while(strlen($aux2) >= $size) {
                $solution .= substr_count(substr($aux2,0,$size), '1') % 2 == 0 ? '1' : '0';
                if(strlen($solution) == $solutionLength) {
                    return $solution;
                    }
                $aux2 = substr($aux2, $size);
            }
        }
    }
}

