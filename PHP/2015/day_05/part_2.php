<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$total = 0;

foreach($data as $line){
    $processLine = $line;

    $findDouble = false;

    while(!$findDouble && strlen($processLine) > 3){
        $double = $processLine[0].$processLine[1];
        $auxLine =substr($processLine,2);
        if(strpos($auxLine,$double) !== false) {
            $findDouble = true;
        }
        $processLine = substr($processLine,1);
    }

    if(!$findDouble){
        continue;
    }

    $processLine = $line;

    $findRepeat = false;
    while(!$findRepeat && strlen($processLine) > 2){
        if($processLine[0] == $processLine[2]) {
            $total++;
            $findRepeat = true;
        }
        $processLine = substr($processLine,1);
    }
}

echo 'Solution: '.$total.PHP_EOL;
echo 'Tiempo: '.number_format(microtime(true) - $start,2);
