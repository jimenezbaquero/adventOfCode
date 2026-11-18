<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$solution = 0;
$program = [];

$program = array_map(function($v) {return intval($v);},explode(",", $data[0]));
$max = PHP_INT_MIN;
$aux = [0, 1, 2, 3, 4];
$phases = [5, 6, 7, 8, 9];
$amps = ['A', 'B', 'C', 'D', 'E'];

$allCombinations = permutations($aux);

foreach($allCombinations as $combination){
    echo 'Testing combination: '.implode(',',$combination).PHP_EOL;

    $states = [
        'A' => ['program' => $program, 'pos' => 0, 'next' => 'B', 'input' => [0], 'halted' => false],
        'B' => ['program' => $program, 'pos' => 0, 'next' => 'C', 'input' => [], 'halted' => false],
        'C' => ['program' => $program, 'pos' => 0, 'next' => 'D', 'input' => [], 'halted' => false],
        'D' => ['program' => $program, 'pos' => 0, 'next' => 'E', 'input' => [], 'halted' => false],
        'E' => ['program' => $program, 'pos' => 0, 'next' => 'A', 'input' => [], 'halted' => false],
    ];

    foreach($combination as $key => $value){
        $states[$amps[$key]]['input'] = array_merge(
            [$phases[$value]],
            $states[$amps[$key]]['input']
        );
    }

    $activeAmp = 'A';

    while(true) {
        $aux = executeProgram(
            $states[$activeAmp]['program'],
            $states[$activeAmp]['input'],
            $states[$activeAmp]['pos']
        );

        if($aux['error']) {
            continue 2;
        }

        $states[$activeAmp]['program'] = $aux['program'];
        $states[$activeAmp]['pos'] = $aux['pos'];
        $states[$activeAmp]['input'] = [];

        $nextAmp = $states[$activeAmp]['next'];



        if(!empty($aux['outputs'])) {
            $states[$nextAmp]['input'] = array_merge(
                $states[$nextAmp]['input'],
                $aux['outputs']
            );
        }

        if($aux['finish']) {
            $states[$activeAmp]['halted'] = true;

            if($activeAmp == 'E') {
                $signal = end($aux['outputs']);
                if($signal > $max) {
                    $max = $signal;
                }
                continue 2;
            }
        }

        if($states[$nextAmp]['halted']) {
            continue 2;
        }

        $activeAmp = $nextAmp;
    }
}

$solution = $max;

echo 'Solution : '.$solution.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start, 2).PHP_EOL;
echo 'Memory used: '.number_format(memory_get_peak_usage() / 1024 / 1024, 2).' MB'.PHP_EOL;
echo 'Memory reserved: '.number_format(memory_get_peak_usage(true) / 1024 / 1024, 2).' MB'.PHP_EOL;

function executeProgram($program, $inputs, $pos) {
    $response = [];
    $size = count($program);
    $numParams = [
        1 => 3,
        2 => 3,
        3 => 1,
        4 => 1,
        5 => 2,
        6 => 2,
        7 => 3,
        8 => 3,
        99 => 0
    ];

    while ($pos < $size) {
        $values = [];
        $instruction = $program[$pos];
        $opcode = $instruction % 100;

        if(!isset($numParams[$opcode])){
            return ['outputs' => false, 'finish' => true, 'error' => true, 'pos' => $pos , 'program' => $program];
        }

        if ($opcode === 99) {
           return ['outputs' => $response, 'finish' => true, 'error' => false, 'pos' => $pos , 'program' => $program];
        }
        $numValue = 1;

        for ($i = 0; $i < $numParams[$opcode]; $i++) {
            $dividend = 10 ** ($i + 2);
            $mode = intdiv($instruction, $dividend) % 10;
            $values[$numValue] = [
                'value' => $program[$pos + $numValue],
                'mode' => $mode
            ];
            $numValue++;
        }

        switch ($opcode) {
            case 1:
            case 2:
            case 7:
            case 8:
                $value1 = $values[1]['mode'] === 0 ? $program[$values[1]['value']] : $values[1]['value'];
                $value2 = $values[2]['mode'] === 0 ? $program[$values[2]['value']] : $values[2]['value'];
                $destiny = $values[3]['value'];
                $advance = 4;
                break;
            case 3:
                $destiny = $values[1]['value'];
                $advance = 2;
                break;
            case 4:
                $output = $values[1]['mode'] === 0 ? $program[$values[1]['value']] : $values[1]['value'];
                $advance = 2;
                break;
            case 5:
                $value1 = $values[1]['mode'] === 0 ? $program[$values[1]['value']] : $values[1]['value'];
                $value2 = $values[2]['mode'] === 0 ? $program[$values[2]['value']] : $values[2]['value'];
                if ($value1 === 0) {
                    $advance = 3;
                } else {
                    $advance = $value2 - $pos;
                }
                break;
            case 6:
                $value1 = $values[1]['mode'] === 0 ? $program[$values[1]['value']] : $values[1]['value'];
                $value2 = $values[2]['mode'] === 0 ? $program[$values[2]['value']] : $values[2]['value'];
                if ($value1 != 0) {
                    $advance = 3;
                } else {
                    $advance = $value2 - $pos;
                }
                break;
            default:
                return ['outputs' => false, 'finish' => true, 'error' => true, 'pos' => $pos , 'program' => $program];
        }

        switch ($opcode) {
            case 1:
                $program[$destiny] = $value1 + $value2;
                break;
            case 2:
                $program[$destiny] = $value1 * $value2;
                break;
            case 3:
                if(!empty($inputs)){
                    $input = array_shift($inputs);
                }else{
                    return ['outputs' => $response, 'finish' => false, 'error' => false, 'pos' => $pos , 'program' => $program];
                }
                $program[$destiny] = $input;
                break;
            case 4:
                $response[] = $output;
                break;
            case 7:
                $program[$destiny] = $value1 < $value2 ? 1 : 0;
                break;
            case 8:
                $program[$destiny] = $value1 === $value2 ? 1 : 0;
                break;
        }
        $pos += $advance;
    }
    return ['outputs' => $response, 'finish' => false, 'error' => false, 'pos' => $pos , 'program' => $program];
}
