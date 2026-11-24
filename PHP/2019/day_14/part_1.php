<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$ingredients = [];
$solution = 0;

foreach ($data as $line) {
   $aux = explode(' => ', $line);
   $aux2 = explode(' ', $aux[1]);
   $aux1 = explode(', ', $aux[0]);
   $aux3 = [];
   foreach ($aux1 as $ingredient) {
       $ingredient = explode(' ', $ingredient);
       $aux3[] = [
           'amount' => intval($ingredient[0]),
           'ingredient' => $ingredient[1],
       ];
   }
   $ingredients[$aux2[1]] = [
       'amount' => intval($aux2[0]),
       'ingredients' => $aux3,
   ];
}

$ingredient = 'FUEL';
$surplus = [];
$solution = calculateOre($ingredient, 1, $ingredients, $surplus);

echo 'Solution : '.$solution.PHP_EOL;
echo 'Time: '.number_format(microtime(true) - $start, 2).PHP_EOL;
echo 'Memory used: '.number_format(memory_get_peak_usage() / 1024 / 1024, 2).' MB'.PHP_EOL;
echo 'Memory reserved: '.number_format(memory_get_peak_usage(true) / 1024 / 1024, 2).' MB'.PHP_EOL;

function calculateOre($ingredient, $amount, $ingredients, &$surplus) {
    if ($ingredient === 'ORE') {
        return $amount;
    }

    $available = $surplus[$ingredient] ?? 0;
    $used = min($amount, $available);

    $amount -= $used;
    $surplus[$ingredient] = $available - $used;

    if ($amount === 0) {
        return 0;
    }

    $reaction = $ingredients[$ingredient];
    $reactions = (int) ceil($amount / $reaction['amount']);
    $extra = ($reactions * $reaction['amount']) - $amount;

    $surplus[$ingredient] += $extra;

    $ore = 0;

    foreach ($reaction['ingredients'] as $component) {
        $ore += calculateOre(
            $component['ingredient'],
            $component['amount'] * $reactions,
            $ingredients,
            $surplus
        );
    }

    return $ore;
}
