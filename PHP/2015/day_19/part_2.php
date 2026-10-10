<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$changes = [];

$changeInstructions = false;

foreach ($data as $line) {
    if (empty($line)){
        $changeInstructions = true;
        continue;
    }
    if(!$changeInstructions){
        $aux = explode(" => ", $line);
        $changes[] = [
            'original' => $aux[0],
            'change' => $aux[1],
        ];
    }else{
        $molecule = $line;
    }
}

preg_match_all('/[A-Z][a-z]?/', $molecule, $matches);

$total = count($matches[0]);
$rn = substr_count($molecule, 'Rn');
$ar = substr_count($molecule, 'Ar');
$y = substr_count($molecule, 'Y');

$steps = $total - $rn - $ar - 2 * $y - 1;

echo 'Solution: ' .$steps.  PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2);

