<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$totalCharacters = 0;
$totalStrings = 0;

foreach ($data as $line) {
    $length = strlen($line);
    $totalCharacters += $length;

    for ($i = 1; $i < $length - 1; $i++) {
        if ($line[$i] === '\\') {
            if ($line[$i + 1] === 'x') {
                $totalStrings++;
                $i += 3;
            } else {
                $totalStrings++;
                $i++;
            }
        } else {
            $totalStrings++;
        }
    }
}

echo 'Solution: ' . ($totalCharacters - $totalStrings) . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2);
