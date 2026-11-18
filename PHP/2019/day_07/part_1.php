<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$solution = 0;
$program = [];

$program = array_map(function($v) {return intval($v);},explode(",", $data[0]));
$max = PHP_INT_MIN;
$maxSignal = 0;
$inputs = [];
$amps = [0, 1, 2, 3, 4];
$outputs = [];

$allCombinations = permutations($amps);

foreach($allCombinations as $combination){
    $ampA = $combination[0];
    $inputs = [$ampA];
    if(!isset($outputs[$ampA]['data'])) {
        $outputs[$ampA]['data'] = executeProgram($program, $inputs);
    }
    if($outputs[$ampA]['data'] == false){
        continue;
    }

    $ampB = $combination[1];
    $inputs = array_merge([$ampB], $outputs[$ampA]['data']);
    if(!isset($outputs[$ampA][$ampB]['data'])) {
        $outputs[$ampA][$ampB]['data'] = executeProgram($program, $inputs);
    }
    if($outputs[$ampA][$ampB]['data'] == false){
        continue;
    }

    $ampC = $combination[2];
    $inputs = array_merge([$ampC], $outputs[$ampA][$ampB]['data']);
    if(!isset($outputs[$ampA][$ampB][$ampC]['data'])) {
        $outputs[$ampA][$ampB][$ampC]['data'] = executeProgram($program, $inputs);
    }
    if($outputs[$ampA][$ampB][$ampC]['data'] == false){
        continue;
    }

    $ampD = $combination[3];
    $inputs = array_merge([$ampD], $outputs[$ampA][$ampB][$ampC]['data']);
    if(!isset($outputs[$ampA][$ampB][$ampC][$ampD]['data'])) {
        $outputs[$ampA][$ampB][$ampC][$ampD]['data'] = executeProgram($program, $inputs);
    }
    if($outputs[$ampA][$ampB][$ampC][$ampD]['data'] == false){
        continue;
    }

    $ampE = $combination[4];
    $inputs = array_merge([$ampE], $outputs[$ampA][$ampB][$ampC][$ampD]['data']);
    $aux = executeProgram($program, $inputs);
    if($aux !== false){
        $signal = array_pop($aux);
        if($signal > $max) {
            $max = $signal;
            $maxSignal = implode(',',$combination);
        }
    }
}

$solution = $max;

echo 'Solution : '.$solution.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start, 2).PHP_EOL;
echo 'Memory used: '.number_format(memory_get_peak_usage() / 1024 / 1024, 2).' MB'.PHP_EOL;
echo 'Memory reserved: '.number_format(memory_get_peak_usage(true) / 1024 / 1024, 2).' MB'.PHP_EOL;

function executeProgram($program, $inputs) {
    $response = [];
    $size = count($program);
    $pos = 0;
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
    $numInput = 0;

    while ($pos < $size) {
        $values = [];
        $instruction = $program[$pos];
        $opcode = $instruction % 100;

        if(!isset($numParams[$opcode])){
            return false;
        }

        if ($opcode === 99) {
            break;
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
                return false;
        }

        switch ($opcode) {
            case 1:
                $program[$destiny] = $value1 + $value2;
                break;
            case 2:
                $program[$destiny] = $value1 * $value2;
                break;
            case 3:
                if(isset($inputs[$numInput])){
                    $input = $inputs[$numInput++];
                }else{
                    $input = 0;
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
    return $response;
}
