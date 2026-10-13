
<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$code = $data[0];

$total = calculateLength($code);

echo 'Solution: ' . $total . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2);

function calculateLength($code)
{
    $total = 0;
    $amount = '';
    $length = '';
    $inside = false;
    $readAmount = false;
    $readLength = false;
    
    for ($i = 0; $i < strlen($code); $i++) {
        if ($code[$i] == '(') {
            $inside = true;
            $readLength = true;
        } else if ($code[$i] == ')') {
            $inside = false;
            $readAmount = false;
            
            $amount = intval($amount);
            $length = intval($length);
            
            $aux = substr($code, $i + 1, $length);
            
            $total += calculateLength($aux) * $amount;
            
            $i += $length;
            $length = '';
            $amount = '';
            
        } else if ($code[$i] == 'x') {
            $readAmount = true;
            $readLength = false;
        } else {
            if ($inside) {
                if ($readLength) {
                    $length .= $code[$i];
                } else if ($readAmount) {
                    $amount .= $code[$i];
                }
            } else {
                $total++;
            }
        }
    }
    
    return $total;
}
