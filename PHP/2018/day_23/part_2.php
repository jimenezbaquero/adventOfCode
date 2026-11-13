
<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$nanobots = [];

$minX = PHP_INT_MAX;
$minY = PHP_INT_MAX;
$minZ = PHP_INT_MAX;

$maxX = PHP_INT_MIN;
$maxY = PHP_INT_MIN;
$maxZ = PHP_INT_MIN;

foreach ($data as $line) {
    
    $aux = explode('>, ', $line);
    $coordinates = explode(',', str_replace('pos=<', '', $aux[0]));
    
    $x = intval($coordinates[0]);
    $y = intval($coordinates[1]);
    $z = intval($coordinates[2]);
    
    $radius = intval(str_replace('r=', '', $aux[1]));
    
    $nanobots[] = [
        'x' => $x,
        'y' => $y,
        'z' => $z,
        'radius' => $radius
    ];
    
    $minX = min($minX, $x - $radius);
    $minY = min($minY, $y - $radius);
    $minZ = min($minZ, $z - $radius);
    
    $maxX = max($maxX, $x + $radius);
    $maxY = max($maxY, $y + $radius);
    $maxZ = max($maxZ, $z + $radius);
}

$size = 1;

$maxSize = max(
    $maxX - $minX + 1,
    $maxY - $minY + 1,
    $maxZ - $minZ + 1
);

while ($size < $maxSize) {
    $size *= 2;
}

$queue = new CubeQueue();
$queue->setExtractFlags(SplPriorityQueue::EXTR_DATA);

$initialCube = [
    'x' => $minX,
    'y' => $minY,
    'z' => $minZ,
    'size' => $size
];

$initialCube['bots'] = countNanobotsInCube($initialCube, $nanobots);
$initialCube['distance'] = getDistanceToOrigin($initialCube);

$queue->insert($initialCube, [
    'bots' => $initialCube['bots'],
    'distance' => $initialCube['distance'],
    'size' => $initialCube['size']
]);

$bestCount = 0;
$bestDistance = PHP_INT_MAX;
$bestPoint = [];

while (!$queue->isEmpty()) {
    
    $cube = $queue->extract();
    
    if (
        $cube['bots'] < $bestCount ||
        (
            $cube['bots'] === $bestCount &&
            $cube['distance'] >= $bestDistance
        )
    ) {
        break;
    }
    
    if ($cube['size'] === 1) {
        
        $bestCount = $cube['bots'];
        $bestDistance = $cube['distance'];
        
        $bestPoint = [
            'x' => $cube['x'],
            'y' => $cube['y'],
            'z' => $cube['z']
        ];
        
        continue;
    }
    
    $newSize = intdiv($cube['size'], 2);
    
    foreach ([0, $newSize] as $dx) {
        foreach ([0, $newSize] as $dy) {
            foreach ([0, $newSize] as $dz) {
                
                $newCube = [
                    'x' => $cube['x'] + $dx,
                    'y' => $cube['y'] + $dy,
                    'z' => $cube['z'] + $dz,
                    'size' => $newSize
                ];
                
                $newCube['bots'] = countNanobotsInCube($newCube, $nanobots);
                
                if ($newCube['bots'] < $bestCount) {
                    continue;
                }
                
                $newCube['distance'] = getDistanceToOrigin($newCube);
                
                if (
                    $newCube['bots'] === $bestCount &&
                    $newCube['distance'] >= $bestDistance
                ) {
                    continue;
                }
                
                $queue->insert($newCube, [
                    'bots' => $newCube['bots'],
                    'distance' => $newCube['distance'],
                    'size' => $newCube['size']
                ]);
            }
        }
    }
}

echo 'Point: '.PHP_EOL;
print_r($bestPoint);

echo 'Nanobots: '.$bestCount.PHP_EOL;
echo 'Solution: '.$bestDistance.PHP_EOL;

echo 'Time: '.round(microtime(true) - $start, 4).PHP_EOL;
echo 'Memory used: '.round(memory_get_usage() / 1024 / 1024, 2).' MB'.PHP_EOL;
echo 'Memory reserved: '.round(memory_get_peak_usage() / 1024 / 1024, 2).' MB'.PHP_EOL;


function countNanobotsInCube(array $cube, array $nanobots): int
{
    $count = 0;
    
    $maxX = $cube['x'] + $cube['size'] - 1;
    $maxY = $cube['y'] + $cube['size'] - 1;
    $maxZ = $cube['z'] + $cube['size'] - 1;
    
    foreach ($nanobots as $nanobot) {
        
        $dx = 0;
        $dy = 0;
        $dz = 0;
        
        if ($nanobot['x'] < $cube['x']) {
            $dx = $cube['x'] - $nanobot['x'];
        } elseif ($nanobot['x'] > $maxX) {
            $dx = $nanobot['x'] - $maxX;
        }
        
        if ($nanobot['y'] < $cube['y']) {
            $dy = $cube['y'] - $nanobot['y'];
        } elseif ($nanobot['y'] > $maxY) {
            $dy = $nanobot['y'] - $maxY;
        }
        
        if ($nanobot['z'] < $cube['z']) {
            $dz = $cube['z'] - $nanobot['z'];
        } elseif ($nanobot['z'] > $maxZ) {
            $dz = $nanobot['z'] - $maxZ;
        }
        
        if ($dx + $dy + $dz <= $nanobot['radius']) {
            $count++;
        }
    }
    
    return $count;
}


function getDistanceToOrigin(array $cube): int
{
    $distance = 0;
    
    foreach (['x', 'y', 'z'] as $axis) {
        
        $min = $cube[$axis];
        $max = $min + $cube['size'] - 1;
        
        if ($min > 0) {
            $distance += $min;
        } elseif ($max < 0) {
            $distance -= $max;
        }
    }
    
    return $distance;
}


class CubeQueue extends SplPriorityQueue
{
    public function compare(mixed $priority1, mixed $priority2): int
    {
        if ($priority1['bots'] !== $priority2['bots']) {
            return $priority1['bots'] <=> $priority2['bots'];
        }
        
        if ($priority1['distance'] !== $priority2['distance']) {
            return $priority2['distance'] <=> $priority1['distance'];
        }
        
        return $priority2['size'] <=> $priority1['size'];
    }
}
