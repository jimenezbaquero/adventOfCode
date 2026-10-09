<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$total = 0;

$json = $data[0];
$findNumber = false;
$number = '';
foreach (str_split($json) as $character) {
    if (is_numeric($character)){
        $number .= $character;
        $findNumber = true;
    }else{
        if($character === '-'){
            $findNumber = true;
            $number .= $character;
        }else if ($findNumber) {
            $total += intval($number);
            $number = '';
            $findNumber = false;
        }
    }
}

echo 'Solution: ' . $total . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2);
