<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$salt = $data[0];

$solution = 0;

$hashes = [];

$validKeys = 0;
$actualKey = 0;

while ($validKeys < 64) {
    $hash = calculateHash($actualKey, $salt, $hashes);
    if (!empty($hash['three'])) {
        $char = $hash['three'];
        for ($i = 1; $i <= 1000; $i++) {
            $aux = calculateHash($actualKey + $i, $salt, $hashes);
            if (in_array($char, $aux['five'])) {
                $validKeys++;
                break;
            }
        }
    }
    unset($hashes[$salt.$actualKey]);
    $actualKey++;
    
}

echo 'Solution: '.($actualKey - 1).PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start,2);

function calculateHash($key, $salt, &$hashes) {
    $key = $salt . $key;
    if (!isset($hashes[$key])) {
        $aux = md5($key);
        for ($i = 0; $i < 2016; $i++) {
            $aux = md5($aux);
        }
        
        preg_match('/(.)\1{2}/', $aux, $three);
        preg_match_all('/(.)\1{4}/', $aux, $five);
        
        $hashes[$key] = [
            'hash' => $aux,
            'three' => $three[1] ?? null,
            'five' => $five[1] ?? []
        ];
    }
    
    return $hashes[$key];
}