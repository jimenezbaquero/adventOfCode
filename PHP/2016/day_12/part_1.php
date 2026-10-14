<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$instructions = [];

$state = [
    'a' => 0,
    'b' => 0,
    'c' => 0,
    'd' => 0,
];

foreach ($data as $line) {
    $aux = explode(" ", $line);
    $instructions[] = [
        'operation' => $aux[0],
        'value' => $aux[1],
        ...(isset($aux[2])? ['value2' => $aux[2]]:[])
    ];
}

$instructionNumber = 0;
$hasNextInstruction = true;
while($hasNextInstruction) {
$instruction = $instructions[$instructionNumber];
    switch ($instruction['operation']) {
        case 'cpy':
            if(is_numeric($instruction['value'])) {
                $value = intval($instruction['value']);
            }else{
                $value = $state[$instruction['value']];
            }
            $state[$instruction['value2']] = $value;
            $instructionNumber++;
            break;
        case 'inc';
            $state[$instruction['value']]++;
            $instructionNumber++;
            break;
        case 'dec':
            $state[$instruction['value']]--;
            $instructionNumber++;
            break;
        case 'jnz':
            if(is_numeric($instruction['value'])) {
                $value = intval($instruction['value']);
            }else{
                $value = $state[$instruction['value']];
            }
            if($value == 0) {
                $instructionNumber++;
            }else {
                $instructionNumber = $instructionNumber + $instruction['value2'];
            }
            break;
    }
    
    if($instructionNumber < 0 || $instructionNumber >= count($instructions)) {
        $hasNextInstruction = false;
    }
}

$solution = $state['a'];

echo 'Solution: '.$solution.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start,2);

