<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');

$weapons = [
    'dagger' => [
        'name' => 'Dagger',
        'cost' => 8,
        'damage' => 4,
        'armor' => 0
    ],
    'shortsword' => [
        'name' => 'ShortSword',
        'cost' => 10,
        'damage' => 5,
        'armor' => 0
    ],
    'warhammer' => [
        'name' => 'WarHammer',
        'cost' => 25,
        'damage' => 6,
        'armor' => 0
    ],
    'longsword' => [
        'name' => 'LongSword',
        'cost' => 40,
        'damage' => 7,
        'armor' => 0
    ],
    'greataxe' => [
        'name' => 'GreatAxe',
        'cost' => 74,
        'damage' => 8,
        'armor' => 0
    ]
];

$armors = [
    'noarmor' => [
        'name' => 'No Armor',
        'cost' => 0,
        'damage' => 0,
        'armor' => 0
    ],
    'leather' => [
        'name' => 'Leather',
        'cost' => 13,
        'damage' => 0,
        'armor' => 1
    ],
    'chainmail' => [
        'name' => 'ChainMail',
        'cost' => 31,
        'damage' => 0,
        'armor' => 2
    ],
    'splintmail' => [
        'name' => 'SplintMailWarHammer',
        'cost' => 53,
        'damage' => 0,
        'armor' => 3
    ],
    'bandedmail' => [
        'name' => 'MandedMail',
        'cost' => 75,
        'damage' => 0,
        'armor' => 4
    ],
    'platemail' => [
        'name' => 'PlateMail',
        'cost' => 102,
        'damage' => 0,
        'armor' => 5
    ]
];

$rings = [
    'noringL' => [
        'name' => 'No Ring Left',
        'cost' => 0,
        'damage' => 0,
        'armor' => 0
    ],
    'noringR' => [
        'name' => 'No Ring Rigth',
        'cost' => 0,
        'damage' => 0,
        'armor' => 0
    ],
    'damage1' => [
        'name' => 'Damage +1',
        'cost' => 25,
        'damage' => 1,
        'armor' => 0
    ],
    'damage2' => [
        'name' => 'Damage +2',
        'cost' => 50,
        'damage' => 2,
        'armor' => 0
    ],
    'damage3' => [
        'name' => 'Damage +3',
        'cost' => 100,
        'damage' => 3,
        'armor' => 0
    ],
    'defense1' => [
        'name' => 'Defense +1',
        'cost' => 20,
        'damage' => 0,
        'armor' => 1
    ],
    'defense2' => [
        'name' => 'Defense +2',
        'cost' => 40,
        'damage' => 0,
        'armor' => 2
    ],
    'defense3' => [
        'name' => 'Defense +3',
        'cost' => 80,
        'damage' => 0,
        'armor' => 3
    ],
];

$bossStats = [];
foreach ($data as $line) {
    $aux = explode(': ', $line);
    $bossStats[str_replace(' ', '', $aux[0])] = intval($aux[1]);
}

$playerLive = 100;
$solution = 0;

$configurations = [];
foreach ($weapons as $weapon => $value1) {
    foreach ($armors as $armor => $value2) {
        foreach ($rings as $ring1 => $value3) {
            foreach ($rings as $ring2 => $value4) {
                if($ring1 == $ring2){
                    continue;
                }
                $cost = $value1['cost'] + $value2['cost'] + $value3['cost'] + $value4['cost'];
                $configurations[] = [
                    'config' => [
                        $weapon,
                        $armor,
                        $ring1,
                        $ring2
                    ],
                    'damage' => $value1['damage'] + $value2['damage'] + $value3['damage'] + $value4['damage'],
                    'armor' => $value1['armor'] + $value2['armor'] + $value3['armor'] + $value4['armor'],
                    'cost' => $cost
                ];
            }
        }
    }
}

usort($configurations, function ($a, $b) {
    return $b['cost'] <=> $a['cost'];
});

foreach ($configurations as $key => $config) {
    $playerStats = [
        'HitPoints' => $playerLive,
        'Damage' => $config['damage'],
        'Armor' => $config['armor'],
    ];
    if (!winGame($config, $playerStats, $bossStats)) {
        $solution = $config['cost'];
        break;
    }
}


echo 'Solution: ' . $solution . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2);

function winGame($config, $playerStats, $bossStats) {
    $playerLive = $playerStats['HitPoints'];
    $bossLive = $bossStats['HitPoints'];
    $playerHit = max($playerStats['Damage'] - $bossStats['Armor'], 1);
    $bossHit = max($bossStats['Damage'] - $playerStats['Armor'], 1);
    while (true) {
        $bossLive -= $playerHit;
        if ($bossLive <= 0) {
            return true;
        }
        
        $playerLive -= $bossHit;
        if ($playerLive <= 0) {
            return false;
        }
        
    }
}