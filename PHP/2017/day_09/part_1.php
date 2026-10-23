<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$solution = 0;

$instruction = cleanGarbage($data[0]);
$level = 0;

foreach (str_split($instruction) as $char) {
    if($char == '{'){
        $level++;
    }else if($char == '}'){
        $solution += $level;
        $level--;
    }
}

echo 'Solution : '.$solution.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start, 2).PHP_EOL;
echo 'Memory used: '.number_format(memory_get_peak_usage() / 1024 / 1024, 2).' MB'.PHP_EOL;
echo 'Memory reserved: '.number_format(memory_get_peak_usage(true) / 1024 / 1024, 2).' MB'.PHP_EOL;

function cleanGarbage($line) {
    while(true){
        $openPos = strpos($line, '<');

        if($openPos === false){
            return $line;
        }

        $allCloses = strpos_all($line, '>');

        if(empty($allCloses)){
            return $line;
        }

        $closeIndex = 0;
        $closePos = $allCloses[$closeIndex];

        while ($closePos < $openPos) {
            $closeIndex++;
            if (!isset($allCloses[$closeIndex])) {
                return $line;
            }
            $closePos = $allCloses[$closeIndex];
        }


        do{
            $countCancel = 0;
            for ($i = $closePos - 1; $i > $openPos; $i--) {
                if ($line[$i] == '!') {
                    $countCancel++;
                } else {
                    break;
                }
            }

            if ($countCancel % 2 != 0) {
                $closeIndex++;
                if (!isset($allCloses[$closeIndex])) {
                    return $line;
                }
                $closePos = $allCloses[$closeIndex];
            } else {
                $line = substr_replace($line, '', $openPos, $closePos - $openPos + 1);
            }
        }while($countCancel % 2 != 0);
    }
}
