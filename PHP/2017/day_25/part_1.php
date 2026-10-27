<?php
$start = microtime(true);
require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$initialState = null;
$initialPosition = 0;
$registers = [];
$solution = 0;

$steps = 0;
$blockLine = 0;
$block = [];
$blocks = [];
$state = null;
foreach ($data as $key => $line) {
    $line = str_replace([
        'Begin in state ',
        'Perform a diagnostic checksum after ',
        'steps.',
        'In state ',
        'If the current state is ',
        'Write the value ',
        'Move one slot to the ',
        'Continue with state ',
        '.',
        ':',
        ' ',
        '-'
    ],'',$line);
    if($key == 0){
        $initialState = $line;
    }else if($key == 1){
        $steps = intval($line);
    }else if($line == ''){
        if(!empty($block)){
            $blocks[$state] = $block;
        }
        $state = null;
        $blockLine = 0;
        $block = [];
    }else{
        switch($blockLine){
            case 0:
                $state = $line;
                break;
            case 2:
                $block['condition-0']['write'] = intval($line);
                break;
            case 3:
                $block['condition-0']['move'] = $line;
                break;
            case 4:
                $block['condition-0']['continue'] = $line;
                break;
            case 6:
                $block['condition-1']['write'] = intval($line);
                break;
            case 7:
                $block['condition-1']['move'] = $line;
                break;
            case 8:
                $block['condition-1']['continue'] = $line;
                break;
        }
        $blockLine++;
    }
}
if(!empty($block)){
    $blocks[$state] = $block;
}

$step = 0;
$state = $initialState;
$position = $initialPosition;

while($step < $steps){
    $step++;
    $block = $blocks[$state];
    if(!isset($registers[$position])){
        $registers[$position] = 0;
    }
    $register = $registers[$position];
    if($register == 0) {
        $condition = 'condition-0';
    }else {
        $condition = 'condition-1';
    }

    $registers[$position] = $block[$condition]['write'];
    $position = $block[$condition]['move'] == 'left' ? $position - 1 : $position + 1;
    $state = $block[$condition]['continue'];
}

$solution = count(array_filter($registers,function($e) {return $e == 1;}));

echo 'Solution: '.$solution.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start,2).PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;
