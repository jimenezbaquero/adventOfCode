```php
<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$pass = $data[0];

$queue = [
    [
        'row' => 0,
        'column' => 0,
        'path' => ''
    ]
];

$solution = '';

while (!empty($queue)) {
    
    $current = array_shift($queue);
    
    if ($current['row'] === 3 && $current['column'] === 3) {
        $solution = $current['path'];
        break;
    }
    
    $possibilities = calculatePosibilities($pass, $current['path']);

    foreach ($possibilities as $direction => $open) {
        
        if (!$open) {
            continue;
        }
        
        $newPosition = [
            'row' => $current['row'],
            'column' => $current['column']
        ];
        
        
        switch ($direction) {
            case 'U';
            if($newPosition['row'] === 0) {
                continue 2;
            }
            $newPosition['row'] = $current['row'] - 1;
            break;
            case 'D';
                if($newPosition['row'] === 3) {
                    continue 2;
                }
                $newPosition['row'] = $current['row'] + 1;
            break;
            case 'L';
                if($newPosition['column'] === 0) {
                    continue 2;
                }
                $newPosition['column'] = $current['column'] - 1;
            break;
            case 'R';
                if($newPosition['column'] === 3) {
                    continue 2;
                }
                $newPosition['column'] = $current['column'] + 1;
                break;
        }
        
        $newPosition['path'] = $current['path'].$direction;
        
        $queue[] = $newPosition;
        
    }
}

echo 'Solution : '.$solution.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start, 2).PHP_EOL;
echo 'Memory used: '.number_format(memory_get_peak_usage() / 1024 / 1024, 2).' MB'.PHP_EOL;
echo 'Memory reserved: '.number_format(memory_get_peak_usage(true) / 1024 / 1024, 2).' MB'.PHP_EOL;


function calculatePosibilities($pass, $path) {
    $possibilities = [];
    
    $aux = md5($pass.$path);
    $aux = substr($aux, 0, 4);
    
    $possibilities['U'] = isOpen($aux[0]);
    $possibilities['D'] = isOpen($aux[1]);
    $possibilities['L'] = isOpen($aux[2]);
    $possibilities['R'] = isOpen($aux[3]);
    
    return $possibilities;
}

function isOpen($code) {
    if (in_array($code, ['b', 'c', 'd', 'e', 'f'])) {
        return true;
    }
    
    return false;
}
