<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$solution = 0;
$size = count($data);

foreach ($data as $key1 => $line1) {
    $length = strlen($line1);
    for ($j = $key1 + 1; $j < $size; $j++) {
        $line2 = $data[$j];
        $dif = 0;
        $aux = '';
        for($i = 0; $i < $length; $i++){
            if($line1[$i] !== $line2[$i]){
                $dif++;
                if($dif > 1){
                    break;
                }
            }else{
                $aux .= $line1[$i];
            }
        }
            if($dif === 1) {
                $solution = $aux;
                break 2;
            }


    }
}

echo 'Solution : ' . $solution . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;

