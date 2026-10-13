<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$code = $data[0];

$newCode = '';
$amount = '';
$length ='';
$inside = false;
$readAmount = false;
$readLength = false;

for ($i = 0; $i < strlen($code); $i++) {
    if($code[$i] == '(') {
        $inside = true;
        $readLength = true;
    }else if($code[$i] == ')') {
        $inside = false;
        $readAmount = false;
        $amount = intval($amount);
        $length = intval($length);
        $aux = substr($code, $i+1, $length);
        for($j = 0; $j < $amount ; $j++) {
            $newCode .= $aux;
        }
        $i += $length;
        $length = '';
        $amount = '';
    }else if($code[$i] == 'x') {
        $readAmount = true;
        $readLength = false;
    }else{
        if($inside){
            if($readLength){
                $length .= $code[$i];
            }else if($readAmount){
                $amount .= $code[$i];
            }
        }else{
            $newCode .= $code[$i];
        }
    }
}

$total = strlen($newCode);

echo 'Solution: ' .$total. PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2);

