
<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$floors = [
    'first' => 1,
    'second' => 2,
    'third' => 3,
    'fourth' => 4
];

$items = [
    [
        'element' => 'elerio',
        'type' => 'generator',
        'floor' => 1
    ],
    [
        'element' => 'elerio',
        'type' => 'microchip',
        'floor' => 1
    ],
    [
        'element' => 'dilitio',
        'type' => 'generator',
        'floor' => 1
    ],
    [
        'element' => 'dilitio',
        'type' => 'microchip',
        'floor' => 1
    ],
];

foreach ($data as $line) {
    $line = str_replace(
        ['The ', 'floor contains ', 'a ', 'and ', '-compatible', '.', ','],
        '',
        trim($line)
    );
    
    $aux = explode(" ", $line);
    
    if (str_contains($aux[1], 'nothing')) {
        continue;
    }
    
    $floor = $floors[$aux[0]];
    
    for ($i = 1; $i < count($aux); $i += 2) {
        $items[] = [
            'element' => $aux[$i],
            'type' => $aux[$i + 1],
            'floor' => $floor
        ];
    }
}

$initialState = '1' . implode('', array_column($items, 'floor'));

$initialKey = getStateKey($initialState, $items);

$queue = new SplQueue();
$queue->enqueue([$initialState, 0]);

$processedSituations = [
    $initialKey => true
];

$solution = null;

while (!$queue->isEmpty()) {
    
    [$state, $moves] = $queue->dequeue();
    
    if (isFinished($state)) {
        $solution = $moves;
        break;
    }
    
    $elevator = intval($state[0]);
    
    $availableItems = [];
    
    foreach ($items as $key => $item) {
        if (intval($state[$key + 1]) == $elevator) {
            $availableItems[] = $key;
        }
    }
    
    $combinations = [];
    
    foreach ($availableItems as $key1 => $item1) {
        $combinations[] = [$item1];
        
        foreach ($availableItems as $key2 => $item2) {
            if ($key2 > $key1) {
                $combinations[] = [$item1, $item2];
            }
        }
    }
    
    foreach ([1, -1] as $direction) {
        
        $nextFloor = $elevator + $direction;
        
        if ($nextFloor < 1 || $nextFloor > 4) {
            continue;
        }
        
        foreach ($combinations as $combination) {
            
            $newState = $state;
            
            $newState[0] = (string) $nextFloor;
            
            foreach ($combination as $item) {
                $newState[$item + 1] = (string) $nextFloor;
            }
            
            if (!isCorrectSituation($newState, $items)) {
                continue;
            }
            
            $key = getStateKey($newState, $items);
            
            if (isset($processedSituations[$key])) {
                continue;
            }
            
            $processedSituations[$key] = true;
            
            $queue->enqueue([
                $newState,
                $moves + 1
            ]);
        }
    }
}

echo 'Solution: ' . $solution . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2);


function isFinished(string $state): bool {
    for ($i = 1; $i < strlen($state); $i++) {
        if ($state[$i] != '4') {
            return false;
        }
    }
    
    return true;
}

function isCorrectSituation(string $state, array $items): bool {
    
    $generators = [];
    $microchips = [];
    
    foreach ($items as $key => $item) {
        
        $floor = intval($state[$key + 1]);
        
        if ($item['type'] == 'generator') {
            $generators[$floor][$item['element']] = true;
        } else {
            $microchips[$floor][] = $item['element'];
        }
    }
    
    foreach ($microchips as $floor => $chips) {
        
        if (empty($generators[$floor])) {
            continue;
        }
        
        foreach ($chips as $chip) {
            if (!isset($generators[$floor][$chip])) {
                return false;
            }
        }
    }
    
    return true;
}

function getStateKey(string $state, array $items): string {
    $elements = [];
    
    foreach ($items as $key => $item) {
        $floor = $state[$key + 1];
        
        $type = $item['type'] == 'generator' ? 'g' : 'm';
        $elements[$item['element']][$type] = $floor;
    }
    
    $pairs = [];
    
    foreach ($elements as $element) {
        $pairs[] = $element['g'] . '-' . $element['m'];
    }
    
    sort($pairs);
    return $state[0] . ',' . implode(',', $pairs);
}
