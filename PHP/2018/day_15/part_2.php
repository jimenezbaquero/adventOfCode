<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$solution = 0;

$originalUnits = [];
$originalGrid = [];

foreach ($data as $row => $line) {
    foreach (str_split($line) as $col => $char) {
        $originalGrid[$row][$col] = $char;
        if($char == '#' || $char == '.'){
            continue;
        }

        $originalUnits[$char.'-'.$row.'-'.$col] = [
            'type' => $char,
            'row' => $row,
            'col' => $col,
            'attack' => 3,
            'life' => 200,
        ];
    }
}



$attack = 3;
$winElves = false;
$units = $originalUnits;
while(!$winElves) {
    $elfDie = false;
    $grid = $originalGrid;
    $remaining = [
        'E' => count(array_filter($units, fn($unit) => $unit['type'] === 'E')),
        'G' => count(array_filter($units, fn($unit) => $unit['type'] === 'G'))
    ];
    $iteration = 0;
    while (true) {
        uasort($units, function ($a, $b) {
            return ($a['row'] <=> $b['row'])
                ?: ($a['col'] <=> $b['col']);
        });

        $pendingUnits = $units;
        $allUnits = $units;
        $roundCounted = false;
        foreach ($allUnits as $key => $unit) {
            if (!isset($pendingUnits[$key])) {
                continue;
            }
            unset($pendingUnits[$key]);
            if (empty($pendingUnits) && !$roundCounted) {
                $iteration++;
                $roundCounted = true;
            }

            $unit = $units[$key];
            $enemy = findAdjacentEnemy($unit, $units);
            if (is_null($enemy)) {
                $nextCell = findNextCell($unit, $units, $grid);
                if (!is_null($nextCell)) {
                    $grid[$unit['row']][$unit['col']] = '.';
                    $grid[$nextCell['row']][$nextCell['col']] = $unit['type'];
                    $unit['row'] = $nextCell['row'];
                    $unit['col'] = $nextCell['col'];
                    unset($units[$key]);
                    $units[$unit['type'] . '-' . $unit['row'] . '-' . $unit['col']] = $unit;
                }
                $enemy = findAdjacentEnemy($unit, $units);
            }

            if (!is_null($enemy)) {
                $units[$enemy]['life'] -= $unit['attack'];
                if ($units[$enemy]['life'] <= 0) {
                    $row = $units[$enemy]['row'];
                    $col = $units[$enemy]['col'];
                    $type = $units[$enemy]['type'];
                    $remaining[$type]--;
                    if($type == 'E'){
                        $elfDie = true;
                        break 2;
                    }
                    unset($units[$enemy]);
                    unset($pendingUnits[$enemy]);
                    $grid[$row][$col] = '.';
                    if (empty($pendingUnits) && !$roundCounted) {
                        $iteration++;
                        $roundCounted = true;
                    }
                    if ($remaining[$type] == 0) {
                        break 2;
                    }

                }
            }
        }
    }
    if($elfDie){
        $attack++;
        $units = $originalUnits;
        foreach ($units as $key => $unit) {
            if ($unit['type'] === 'E') {
                $units[$key]['attack'] = $attack;
            }
        }
    }else{
        $winElves = true;
    }
}

$solution = $iteration * array_sum(array_column($units,'life'));

echo 'Solution : ' . $solution . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;

function findAdjacentEnemy($unit, $units): ?string {
    $row = $unit['row'];
    $col = $unit['col'];
    $type = $unit['type'];
    $enemyType = $type === 'E' ? 'G' : 'E';
    $enemys = [];

    $movements = [-1, 1];

    foreach ($movements as $movement) {
        if(isset($units[$enemyType.'-'.$row.'-'.($col + $movement)])){
            $enemys[$enemyType.'-'.$row.'-'.($col + $movement)] = $units[$enemyType.'-'.$row.'-'.($col + $movement)];
        }
        if(isset($units[$enemyType.'-'.($row + $movement).'-'.($col)])){
            $enemys[$enemyType.'-'.($row + $movement).'-'.($col)] = $units[$enemyType.'-'.($row + $movement).'-'.($col)];
        }
    }

    uasort($enemys, function ($a, $b) {
        return ($a['life'] <=> $b['life'])
            ?: ($a['row'] <=> $b['row'])
                ?: ($a['col'] <=> $b['col']);
    });

    return array_key_first($enemys);
}


function findNextCell($unit, $units, $grid): ?array {
    $row = $unit['row'];
    $col = $unit['col'];

    $enemyType = $unit['type'] === 'E' ? 'G' : 'E';

    $targets = [];

    $movements = [
        [-1, 0],
        [0, -1],
        [0, 1],
        [1, 0]
    ];

    foreach ($units as $enemy) {
        if ($enemy['type'] !== $enemyType) {
            continue;
        }

        foreach ($movements as [$moveRow, $moveCol]) {
            $targetRow = $enemy['row'] + $moveRow;
            $targetCol = $enemy['col'] + $moveCol;

            if (($grid[$targetRow][$targetCol] ?? '#') !== '.') {
                continue;
            }

            $targets[$targetRow . ',' . $targetCol] = [
                'row' => $targetRow,
                'col' => $targetCol
            ];
        }
    }

    if (empty($targets)) {
        return null;
    }

    $startIndex = $row . ',' . $col;

    $queue = [
        $startIndex => [
            'row' => $row,
            'col' => $col,
            'firstMoves' => []
        ]
    ];

    $visited = [$startIndex => true];

    while (!empty($queue)) {
        $nextQueue = [];

        foreach ($queue as $position) {
            foreach ($movements as [$moveRow, $moveCol]) {
                $nextRow = $position['row'] + $moveRow;
                $nextCol = $position['col'] + $moveCol;
                $index = $nextRow . ',' . $nextCol;

                if (isset($visited[$index])) {
                    continue;
                }

                if (($grid[$nextRow][$nextCol] ?? '#') !== '.') {
                    continue;
                }

                if (!isset($nextQueue[$index])) {
                    $nextQueue[$index] = [
                        'row' => $nextRow,
                        'col' => $nextCol,
                        'firstMoves' => []
                    ];
                }

                if (empty($position['firstMoves'])) {
                    $firstMove = [
                        'row' => $nextRow,
                        'col' => $nextCol
                    ];

                    $firstIndex = $nextRow . ',' . $nextCol;

                    $nextQueue[$index]['firstMoves'][$firstIndex] = $firstMove;
                } else {
                    foreach ($position['firstMoves'] as $firstIndex => $firstMove) {
                        $nextQueue[$index]['firstMoves'][$firstIndex] = $firstMove;
                    }
                }
            }
        }

        if (empty($nextQueue)) {
            return null;
        }

        $found = array_intersect_key($nextQueue, $targets);

        if (!empty($found)) {
            usort($found, function ($a, $b) {
                return ($a['row'] <=> $b['row'])
                    ?: ($a['col'] <=> $b['col']);
            });

            $firstMoves = array_values($found[0]['firstMoves']);

            usort($firstMoves, function ($a, $b) {
                return ($a['row'] <=> $b['row'])
                    ?: ($a['col'] <=> $b['col']);
            });

            return $firstMoves[0];
        }

        foreach ($nextQueue as $index => $position) {
            $visited[$index] = true;
        }

        $queue = $nextQueue;
    }

    return null;
}

