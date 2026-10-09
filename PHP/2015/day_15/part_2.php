<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$size=100;
$ingredients = [];

foreach ($data as $line) {
    $aux = explode(": ", $line);
    $aux2 = explode(", ", $aux[1]);
    $ingredients[$aux[0]] = [
        'capacity' => intval(str_replace('capacity ','', $aux2[0])),
        'durability' => intval(str_replace('durability ','', $aux2[1])),
        'flavor' => intval(str_replace('flavor ','', $aux2[2])),
        'texture' => intval(str_replace('texture ','', $aux2[3])),
        'calories' => intval(str_replace('calories ','', $aux2[4]))
    ];
}

$bestProduct = 0;

foreach (combinations($ingredients, 100) as $combination) {
    $total = [
        'capacity' => 0,
        'durability' => 0,
        'flavor' => 0,
        'texture' => 0,
        'calories' => 0
    ];
    foreach ($combination as $ingredient => $amount) {
        foreach ($total as $key => $value) {
            $total[$key] += $amount * $ingredients[$ingredient][$key];
        }
    }
    
    if($total['calories'] !== 500){
        continue;
    }
    
    foreach ($total as $key => $value) {
        $total[$key] = max($value, 0);
    }
    
    $product = $total['capacity']*$total['durability']*$total['flavor']*$total['texture'];
    if ($product > $bestProduct) {
        $bestProduct = $product;
    }
}

echo 'Solution: ' . $bestProduct . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2);
