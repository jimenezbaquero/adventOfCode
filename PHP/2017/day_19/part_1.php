<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');
$solution = '';
$map = [];
$row = 0;

foreach ($data as $line) {
    $aux = str_split($line);
    foreach ($aux as $key => $char) {
        $map[$row][$key] = $char;
    }
    $row++;
}

$row = 0;
$column = 0;

$column = array_search('|', $map[0]);

$existNext = true;
$direction = 'down';
$next = $map[$row][$column];

while ($existNext){
    if($next == ' '){
        break;
    }
    switch ($next){
        case '|':
        case '-':
            switch ($direction){
                case 'down':
                    $row++;
                    break;
                case 'up':
                    $row--;
                    break;
                case 'right':
                    $column++;
                    break;
                case 'left':
                    $column--;
                    break;
            }
            break;
        case '+':
            if(($direction == 'right' || $direction == 'left') && ($map[$row + 1][$column] != ' ' || $map[$row - 1][$column] != ' ')){
                if($map[$row + 1][$column] != ' '){
                    $row++;
                    $direction = 'down';
                }else if ($map[$row - 1][$column] != ' '){
                    $row--;
                    $direction = 'up';
                }else{
                    $existNext = false;
                }
            }else if(($direction == 'up' || $direction == 'down') && ($map[$row ][$column + 1] != ' ' || $map[$row][$column - 1] != ' ')) {
                if($map[$row][$column + 1] != ' '){
                    $column++;
                    $direction = 'right';
                }else if ($map[$row][$column - 1] != ' '){
                    $column--;
                    $direction = 'left';
                }else{
                    $existNext = false;
                }
            }
            break;
        default:
            $solution .= $next;
            switch ($direction){
                case 'down':
                    $row++;
                    break;
                case 'up':
                    $row--;
                    break;
                case 'right':
                    $column++;
                    break;
                case 'left':
                    $column--;
                    break;
            }
    }
    if($map[$row][$column] != ' '){
        $next = $map[$row][$column];
    }else{
        $existNext = false;
    }
    echo 'Proxima: '.$row.'-'.$column.', dirección: '.$direction.', siguiente: '.$next.PHP_EOL;
}

echo 'Solution: '.$solution.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start,2).PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;
