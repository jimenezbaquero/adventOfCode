<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$solution = 0;
$nodes = [];
$workers = [];
$dictionary = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm','n','o','p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];
$workTime = 60;
$numWorkers = 5;

for ($i = 1; $i <= $numWorkers; $i++) {
    $workers[$i] = null;
}

foreach ($data as $line) {
    $aux = explode(" ", $line);
    $nodes[$aux[1]]['name'] = $aux[1];
    $nodes[$aux[1]]['next'][] = $aux[7];
    $nodes[$aux[7]]['prev'][] = $aux[1];
    $nodes[$aux[1]]['time'] = $workTime + array_search(strtolower($aux[1]), $dictionary) + 1;
    $nodes[$aux[7]]['time'] = $workTime + array_search(strtolower($aux[7]), $dictionary) + 1;
}

$initialNodes = [];
foreach ($nodes as $node) {
    if(!isset($node['prev'])) {
        $queue[] = $node['name'];
    }
}

sort($queue);

$visits = [];
$time = 0;

while(true) {
    reviewWorkers($workers, $queue, $time, $nodes, $visits);
    assignNodes($queue, $visits, $nodes, $workers, $time);
    if(count($visits) == count($nodes)) {
        break;
    }
    $time++;
    
}

$solution = $time;

echo 'Solution : ' . $solution . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;

function assignNodes(&$queue, &$visits, $nodes, &$workers, $time): void {
    foreach ($workers as $key => $worker) {
        if (!is_null($worker)) {
            continue;
        }
        
        if (!empty($queue)) {
            $node = array_shift($queue);
            $complete = false;
            $views = 0;
            $total = count($queue);
            while (!$complete && $views <= $total) {
                $complete = true;
                if (isset($nodes[$node]['prev'])) {
                    foreach ($nodes[$node]['prev'] as $prev) {
                        if (!isset($visits[$prev])) {
                            if(!empty($queue)) {
                                $views++;
                                $aux = array_shift($queue);
                                $queue[] = $node;
                                $node = $aux;
                                $complete = false;
                            }else{
                                $queue[] = $node;
                                $node = null;
                                $complete = true;
                            }
                            break;
                        }
                    }
                }
            }
            if(!is_null($node) && $views <= $total) {
                $workers[$key] = [
                    'name' => $node,
                    'time' => $nodes[$node]['time'] + $time,
                ];
            }
        }
        
    }
    $queue = array_unique($queue);
    sort($queue);
}


function reviewWorkers(&$workers, &$queue, $time, $nodes, &$visits): void {
    foreach ($workers as $key => $worker) {
        if (!is_null($worker)) {
            if ($worker['time'] == $time) {
                $visits[$worker['name']] = true;
                if(isset($nodes[$worker['name']]['next'])) {
                    foreach ($nodes[$worker['name']]['next'] as $next) {
                        $queue[] = $next;
                    }
                }
                $workers[$key] = null;
            }
        }
    }
    $queue = array_unique($queue);
    sort($queue);
}