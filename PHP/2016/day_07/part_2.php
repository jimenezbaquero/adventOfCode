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
    $insideGroups = [];
    $outsideGroups = [];
    
    $inside = false;
    $chars = str_split($ip);
    $sizeIp = count($chars);
    foreach ($chars as $key => $char) {
        if ($char == '[') {
            $inside = true;
            continue;
        }
        if ($char == ']') {
            $inside = false;
            continue;
        }
        
        if ($key < $sizeIp - 2) {
            if ($char == $chars[$key + 2]) {
                if (!in_array($chars[$key + 1],['[',']',$char])) {
                    $group = $char . $chars[$key + 1] . $chars[$key + 2];
                    if($inside) {
                        $insideGroups[] = $group;
                    }else{
                        $outsideGroups[] = $group;
                    }
                }
            }
        }
    }
    
    foreach ($insideGroups as $group) {
        $reverseGroup = $group[1].$group[0].$group[1];
        if(in_array($reverseGroup, $outsideGroups)){
            $correctIps++;
            continue 2;
        }
    }
}

echo 'Solution: '.$correctIps.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start,2);

