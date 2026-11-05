<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$solution = 0;

$carts = [];
$grid = [];
$positions = [];

foreach ($data as $row => $line) {
    $aux = str_split($line);
    foreach ($aux as $col => $char) {
        if($char != ' ') {
           switch ($char) {
               case '-':
               case '|':
               case '+':
               case '/':
               case '\\':
                   $grid[$row][$col] = $char;
                   break;
               default:
                   $carts[] = [
                       'row' => $row,
                       'col' => $col,
                       'direction' => $char,
                       'lastTurn' => 'right'
                   ];
                   
                   switch ($char) {
                       case '>':
                       case '<':
                           $grid[$row][$col] = '-';
                           break;
                       case '^':
                       case 'v':
                           $grid[$row][$col] = '|';
                   }
           }
        }
    }
}


$crash = false;

while(!$crash){
    $positions = [];
    usort($carts, function ($a, $b) {
        return ($a['row'] <=> $b['row'])
            ?: ($a['col'] <=> $b['col']);
    });
    foreach ($carts as $key => $cart) {
        $col = $cart['col'];
        $row = $cart['row'];
        $nextRow = $row;
        $nextCol = $col;
        $direction = $cart['direction'];
        $nextDirection = $direction;
        $lastTurn = $cart['lastTurn'];
        $nextTurn = $lastTurn;
        switch ($direction) {
            case '>':
                $nextCol++;
                break;
            case '<':
                $nextCol--;
                break;
            case 'v':
                $nextRow++;
                break;
            case '^':
                $nextRow--;
        }
        $cell = $grid[$nextRow][$nextCol];
        switch ($cell) {
            case '/':
                switch ($direction) {
                    case 'v':
                        $nextDirection = '<';
                        break;
                    case '^':
                        $nextDirection = '>';
                        break;
                    case '>':
                        $nextDirection = '^';
                        break;
                    case '<':
                        $nextDirection = 'v';
                        break;
                }
                break;
            case '\\':
                switch ($direction) {
                    case 'v':
                        $nextDirection = '>';
                        break;
                    case '^':
                        $nextDirection = '<';
                        break;
                    case '>':
                        $nextDirection = 'v';
                        break;
                    case '<':
                        $nextDirection = '^';
                        break;
                }
                break;
            case '+':
                switch ($direction) {
                    case 'v':
                        switch ($lastTurn) {
                            case 'right':
                                $nextDirection = '>';
                                $nextTurn = 'left';
                                break;
                            case 'straight':
                                $nextDirection = '<';
                                $nextTurn = 'right';
                                break;
                            case 'left':
                                $nextTurn = 'straight';
                                break;
                        }
                        break;
                    case '^':
                        switch ($lastTurn) {
                            case 'right':
                                $nextDirection = '<';
                                $nextTurn = 'left';
                                break;
                            case 'straight':
                                $nextDirection = '>';
                                $nextTurn = 'right';
                                break;
                            case 'left':
                                $nextTurn = 'straight';
                                break;
                        }
                        break;
                    case '>':
                        switch ($lastTurn) {
                            case 'right':
                                $nextDirection = '^';
                                $nextTurn = 'left';
                                break;
                            case 'straight':
                                $nextDirection = 'v';
                                $nextTurn = 'right';
                                break;
                            case 'left':
                                $nextTurn = 'straight';
                                break;
                        }
                        break;
                    case '<':
                        switch ($lastTurn) {
                            case 'right':
                                $nextDirection = 'v';
                                $nextTurn = 'left';
                                break;
                            case 'straight':
                                $nextDirection = '^';
                                $nextTurn = 'right';
                                break;
                            case 'left':
                                $nextTurn = 'straight';
                                break;
                        }
                        break;
                }
        }
        
        $index = $nextCol.','.$nextRow;
        if(isset ($positions[$index])) {
            $crash = true;
            $solution = $index;
            break 2;
        }else {
            $positions[$index] = true;
            $carts[$key] = [
                'col' => $nextCol,
                'row' => $nextRow,
                'direction' => $nextDirection,
                'lastTurn' => $nextTurn,
            ];
        }
    }
}

echo 'Solution : ' . $solution . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;
