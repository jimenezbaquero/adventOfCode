<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$total = 0;
$vowels = ['a','e','i','o','u'];
$fails = ['ab','cd','pq','xy'];
$doubles = ['aa','bb','cc','dd','ee','ff','gg','hh','ii','jj','kk','ll','mm','nn','oo','pp','qq','rr','ss','tt','uu','vv','ww','xx','yy','zz'];

foreach($data as $line){
    foreach ($fails as $fail){
        if(strpos($line,$fail) !== false) {
            continue 2;
        }
    }

    $findDouble = false;

    foreach ($doubles as $double){
        if(strpos($line,$double) !== false) {
            $findDouble = true;
            break;
        }
    }

    if(!$findDouble){
        continue;
    }

    $vowelsAmounts = 0;
    foreach(str_split($line) as $character){
        if(in_array($character,$vowels)){
            $vowelsAmounts++;
        }
        if($vowelsAmounts == 3){
            $total++;
            continue 2;
        }
    }
}

echo 'Solution: '.$total.PHP_EOL;
echo 'Tiempo: '.number_format(microtime(true) - $start,2);
