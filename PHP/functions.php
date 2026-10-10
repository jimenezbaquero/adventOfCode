<?php
function loadFile($file,$trim=false){
    $rows = [];
    $input = fopen($file,'rb');
    while ($line = fgets($input)) {
        $line = trim($line,"\r\n\t");
        if($trim) {
            $line = trim($line);
        }
        $rows[] = $line;
    }
    return $rows;
}

function transpose($array) {
    $response = [];
    foreach ($array as $numRow => $row){
        foreach ($row as $numCol => $element){
            $response[$numCol][$numRow] = $element;
        }
    }
    return $response;
}

function paint($array ,$key = null){
    $keys = array_keys($array);
    $minKey = min($keys);
    $maxKey = max($keys);
    for ($i = $minKey; $i <= $maxKey; $i++){
        $text = '';
        for ($j = $minKey; $j <= $maxKey; $j++){
            if(!is_null($key)){
                $text.=$array[$i][$j][$key];
            }else{
                $text .= $array[$i][$j];
            }
        }
        echo $text.PHP_EOL;
    }
    echo '---------------'.PHP_EOL;
}

function paintFull($array){
  foreach ($array as $i=>$row){
    echo implode('',$row).PHP_EOL;
  }
  echo '---------------'.PHP_EOL;
}

function removeFirst($array, $value)
{
    $key = array_search($value, $array, true);

    if ($key !== false) {
        unset($array[$key]);
    }

    return array_values($array);
}

function permutations(array $array): array
{
    if (count($array) <= 1) {
        return [$array];
    }
    
    $result = [];
    
    foreach ($array as $key => $value) {
        $remaining = $array;
        unset($remaining[$key]);
        
        foreach (permutations(array_values($remaining)) as $permutation) {
            $result[] = [$value, ...$permutation];
        }
    }
    
    return $result;
}

function circularPermutations(array $elements): array {
    $first = array_shift($elements);
    $permutations = permutations($elements);
    
    $result = [];
    
    foreach ($permutations as $permutation) {
        array_unshift($permutation, $first);
        $result[] = $permutation;
    }
    
    return $result;
}

function combinations(array $group, int $total): Generator
{
    $keys = array_keys($group);
    $count = count($keys);
    
    yield from generateCombinations($keys, $total, $count, 0, []);
}

function generateCombinations(
    array $keys,
    int $remaining,
    int $count,
    int $index,
    array $combination
): Generator {
    if ($index === $count - 1) {
        $combination[$keys[$index]] = $remaining;
        yield $combination;
        return;
    }
    
    for ($i = 0; $i <= $remaining; $i++) {
        $combination[$keys[$index]] = $i;
        
        yield from generateCombinations(
            $keys,
            $remaining - $i,
            $count,
            $index + 1,
            $combination
        );
    }
}

function factorial($n) {
    $result = 1;
    
    for ($i = 2; $i <= $n; $i++) {
        $result *= $i;
    }
    
    return $result;
}