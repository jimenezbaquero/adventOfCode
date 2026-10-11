<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$instuctions = [];

foreach ($data as $line) {
    $aux = explode(' ',$line);
    $amount = null;
    $direction = null;
    $node = null;
    
    if(isset($aux[2])){
        $direction = $aux[2][0] ;
        $amount = intval(substr($aux[2],1));
    }
    
    if(is_numeric($aux[1])){
        $direction = $aux[1][0] ;
        $amount = intval(substr($aux[1],1));
    }else{
        $node = str_replace(',','',$aux[1]);
    }
    
    $instuctions[] = [
        'name' => $aux[0],
        'node' => $node,
        'amount' => $amount,
        'direction' => $direction,
    ];
}


$state = [];
$instructionNumber = 0;

while ($instructionNumber >= 0 && $instructionNumber < count($instuctions)) {
    $instruction = $instuctions[$instructionNumber];
    switch ($instruction['name']) {
        case('hlf'):
            $state[$instruction['node']] = ($state[$instruction['node']] ?? 0) / 2;
            $instructionNumber++;
            break;
        case('tpl'):
            $state[$instruction['node']] = ($state[$instruction['node']] ?? 0) * 3;
            $instructionNumber++;
            break;
        case('inc'):
            $state[$instruction['node']] = ($state[$instruction['node']] ?? 0) + 1;
            $instructionNumber++;
            break;
        case('jmp'):
            if($instruction['direction'] == '+'){
                $instructionNumber+= $instruction['amount'];
            }else{
                $instructionNumber -= $instruction['amount'];
            }
            break;
        case('jie'):
            if(isset($state[$instruction['node']]) && $state[$instruction['node']] % 2 == 0) {
                if ($instruction['direction'] == '+') {
                    $instructionNumber += $instruction['amount'];
                } else {
                    $instructionNumber -= $instruction['amount'];
                }
            }else{
                $instructionNumber++;
            }
            break;
        case('jio'):
            if(isset($state[$instruction['node']]) && $state[$instruction['node']] == 1) {
                if ($instruction['direction'] == '+') {
                    $instructionNumber += $instruction['amount'];
                } else {
                    $instructionNumber -= $instruction['amount'];
                }
            }else{
                $instructionNumber++;
            }
            break;
    }
}

echo 'Solution: '.$state['b'].PHP_EOL;
echo 'Tiempo: '.number_format(microtime(true) - $start,2);

