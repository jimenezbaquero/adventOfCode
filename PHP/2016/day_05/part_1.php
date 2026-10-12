<?php
$start = microtime(true);

require_once('../../functions.php');

$data = 'uqwqemis';
//$data = 'abc';

$code = '';
$success = 0;
$i = 0;

while ($success < 8) {
    $hash = md5($data.$i++);
    if(str_starts_with($hash,'00000')) {
        $success++;
        $code .= substr($hash, 5, 1);
    }
}

echo 'Solution: '.$code.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start,2);

