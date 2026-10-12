<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$alphabet = 'abcdefghijklmnopqrstuvwxyz';
$alphabetSize = strlen($alphabet);
$rooms = [];
$solution = 0;

foreach ($data as $line) {
    $aux = explode("-", $line);
    $aux2 = explode('[', array_pop($aux));
    $letters = implode('-', $aux);
    $code = intval($aux2[0]);
    $rooms[] = [
        'letters' => $letters,
        'code' => $code,
    ];
}

foreach ($rooms as $room) {
    $changes = [];
    $newString = '';
    foreach (str_split($room['letters']) as $letter) {
        if($letter == '-'){
            $newString .= '-';
            continue;
        }
        if(isset($changes[$letter])) {
            $newString .= $changes[$letter];
            continue;
        }
        $position = (strpos($alphabet, $letter) + $room['code']) % $alphabetSize;
        $newLetter = substr($alphabet,  $position, 1);
        $newString .= $newLetter;
        $changes[$letter] = $newLetter;
    }
    
    if($newString == 'northpole-object-storage'){
        $solution = $room['code'];
        break;
    }
}

echo 'Solution: '.$solution.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start,2);

