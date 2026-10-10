<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$changes = [];
$molecule = '';
$newMolecules = [];

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

foreach ($changes as $change) {
    $search = $change['original'];
    
    preg_match_all('/' . preg_quote($search, '/') . '/', $molecule, $matches, PREG_OFFSET_CAPTURE);
    $positions = array_column($matches[0], 1);
    
    foreach ($positions as $position) {
        $newMolecules[] = substr_replace($molecule, $change['change'], $position, strlen($search));
    }
}

$newMolecules = array_unique($newMolecules);

echo 'Solution: ' .count($newMolecules).  PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2);

