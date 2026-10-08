<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$totalCharacters = 0;
$totalStrings = 0;

foreach ($data as $line) {
    $length = strlen($line);
    $totalStrings += $length;
    $totalCharacters += 2;
    for ($i = 0; $i < $length; $i++) {
        if($line[$i] === '"'){
            $totalCharacters += 2;
        }else if ($line[$i] === '\\') {
            $totalCharacters += 2;
        } else {
            $totalCharacters++;
        }
    }
}

echo 'Solution: ' . ($totalCharacters - $totalStrings) . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2);
