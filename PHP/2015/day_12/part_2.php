<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$json = json_decode($data[0]);

$total = calculateSum($json);

echo 'Solution: ' . $total . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2);

function calculateSum($data){
    if(is_numeric($data)){
        return intval($data);
    }
    
    if(is_string($data) || is_null($data)){
        return 0;
    }
    
    if(is_object($data)) {
        $values = get_object_vars($data);
        if (in_array('red', $values)) {
            return 0;
        }
        $data = $values;
    }
    
    $sum = 0;
    
    foreach ($data as $value){
        $sum += calculateSum($value);
    }
    return $sum;
}