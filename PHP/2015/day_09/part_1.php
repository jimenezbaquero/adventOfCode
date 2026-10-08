<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$response = 0;

$cities = [];
$routes = [];
$completeRoutes = [];

foreach ($data as $line) {
    $line = str_replace(['to ', '= '], '', $line);
    $aux = explode(" ", $line);
    if(!in_array($aux[0], $cities)) {
        $cities[] = $aux[0];
    }
    if(!in_array($aux[1], $cities)) {
        $cities[] = $aux[1];
    }
    $routes[$aux[0]][$aux[1]] = $aux[2];
    $routes[$aux[1]][$aux[0]] = $aux[2];
}

$completeRoutes = makeRoutes(permutations($cities),$routes);

$minRoute = PHP_INT_MAX;

foreach ($completeRoutes as $route) {
    if($route['length'] < $minRoute){
        $minRoute = $route['length'];
    }
}

$response = $minRoute;
echo 'Solution: ' . $response . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2);


function makeRoutes(array $permutations, array $routes): array {
    $response = [];
    foreach ($permutations as $permutation) {
        $totalRoute = 0;
        for($i = 0; $i < count($permutation) - 1; $i++) {
            $totalRoute += $routes[$permutation[$i]][$permutation[$i + 1]];
        }
        $response[] = [
            'length' => $totalRoute,
            'route' => $permutation
            ];
    }
    return $response;
}