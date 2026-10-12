<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$ips = [];
$correctIps = 0;

foreach ($data as $line) {
    $ips[] = $line;
}

foreach ($ips as $ip) {
    $outsideOK = false;
    
    $inside = false;
    $chars = str_split($ip);
    foreach ($chars as $key => $char) {
        if($char == '['){
            $inside = true;
            continue;
        }
        if($char == ']'){
            $inside = false;
            continue;
        }
        if($key < count($chars) - 3) {
            if(!$inside && $outsideOK){
                continue;
            }
            if ($char == $chars[$key + 3] && $char != $chars[$key + 1] && $chars[$key + 1] == $chars[$key + 2]) {
                if ($inside){
                    continue 2;
                } else {
                    $outsideOK = true;
                }
            }
        }
    }
    if($outsideOK){
        $correctIps++;
    }
}

echo 'Solution: '.$correctIps.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start,2);

