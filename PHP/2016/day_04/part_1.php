<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$rooms = [];
$total = 0;

foreach ($data as $line) {
    $aux = explode("-", $line);
    $aux2 = explode('[', array_pop($aux));
    $letters = implode('', $aux);
    $code = intval($aux2[0]);
    $control = str_replace(']', '', $aux2[1]);
    $rooms[] = [
        'letters' => $letters,
        'code' => $code,
        'control' => $control
    ];
}

foreach ($rooms as $key => $room) {
    $aux = [];
    foreach (str_split($room['letters']) as $letter) {
        $aux[$letter] = isset($aux[$letter]) ? $aux[$letter] + 1 : 1;
    }
    arsort($aux);
    
    $aux2 = [];
    foreach ($aux as $letter => $number) {
        $aux2[$number][] = $letter;
    }
    
    foreach ($aux2 as $number => $letters) {
        sort($letters);
        $rooms[$key]['order_letters'][$number] = implode('', $letters);
    }
}

foreach ($rooms as $key => $room) {
    $control = '';
    foreach ($room['order_letters'] as $letters) {
        $control .= substr($letters,0,5-strlen($control));
    }
    
    if($control == $room['control']) {
        $total += $room['code'];
    }
}

echo 'Solution: '.$total.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start,2);

