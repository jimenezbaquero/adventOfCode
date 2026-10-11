<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');


$spells = [
    'magic_missile' => [
        'cost' => 53,
        'damage' => 4,
    ],
    'drain' => [
        'cost' => 73,
        'damage' => 2,
        'health' => 2,
    ],
    'shield' => [
        'cost' => 113,
        'bostArmor' => 7,
        'turns' => 6
    ],
    'poison' => [
        'cost' => 173,
        'damage' => 3,
        'turns' => 6
    ],
    'recharge' => [
        'cost' => 229,
        'mana' => 101,
        'turns' => 5
    ]
];


$bossStats = [];
foreach ($data as $line) {
    $aux = explode(': ', $line);
    $bossStats[str_replace(' ', '', $aux[0])] = intval($aux[1]);
}

$playerLive = 50;
$solution = 0;


$state = [
    'playerLive' => $playerLive,
    'mana' => 500,
    'bossLive' => $bossStats['HitPoints'],
    'bossDamage' => $bossStats['Damage'],
    'shieldArmor' => 0,
    'shield' => 0,
    'poison' => 0,
    'recharge' => 0,
    'manaSpent' => 0,
    'spells' => []
];

$win = false;
$loose = false;
$games = [];
$states[] = $state;
$winGames = [];
$iteration = 0;

$minMana = PHP_INT_MAX;
while (!empty($states)) {
    $states = cleanStates($states);

    $nextStates = [];
    usort($states, function ($a, $b) {
        return $a['manaSpent'] <=> $b['manaSpent'];
    });
    
    foreach ($states as $state) {
        if (isset($state['winner'])) {
            continue;
        }
        if ($state['manaSpent'] > $minMana) {
            continue;
        }

        $state = applySpellPlayer($state, $spells);
        
        if ($state['bossLive'] <= 0) {
            $state['winner'] = 'PLAYER';
            $winGames[] = $state;
            if ($state['manaSpent'] < $minMana) {
                $minMana = $state['manaSpent'];
            }
        } else {
            $games = calculateGames($state, $spells);
            
            foreach ($games as $game) {
                $aux = playPlayer($game, $state, $spells);
                
                if ($aux['bossLive'] <= 0) {
                    $aux['winner'] = 'PLAYER';
                    $winGames[] = $aux;
                    if ($aux['manaSpent'] < $minMana) {
                        $minMana = $aux['manaSpent'];
                    }
                } elseif ($aux['playerLive'] <= 0) {
                    $aux['winner'] = 'BOSS';
                } else {
                    $aux = applySpellBoss($aux, $spells);
                    
                    if ($aux['bossLive'] <= 0) {
                        $aux['winner'] = 'PLAYER';
                        $winGames[] = $aux;
                        if ($aux['manaSpent'] < $minMana) {
                            $minMana = $aux['manaSpent'];
                        }
                    } else {
                        $aux = playBoss($aux);
                        
                        if ($aux['playerLive'] <= 0) {
                            $aux['winner'] = 'BOSS';
                        }else{
                            $nextStates[] = $aux;
                        }
                    }
                }
            }
        }
    }
    $states = $nextStates;
    $iteration++;
}

echo 'Solution: ' . $minMana . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2);

function calculateGames($state, $spells) {
    $games = [];
    foreach ($spells as $key => $spell) {
        if ($key == 'recharge' && $state['recharge'] > 0) {
            continue;
        }
        if ($key == 'shield' && $state['shield'] > 0) {
            continue;
        }
        if ($key == 'poison' && $state['poison'] > 0) {
            continue;
        }
        
        if ($spell['cost'] <= $state['mana']) {
            $games[] = $key;
        }
    }
    return $games;
}

function applySpellPlayer($state, $spells) {
    if ($state['recharge'] > 0) {
        $state['mana'] += $spells['recharge']['mana'];
        $state['recharge']--;
    }
    
    if ($state['poison'] > 0) {
        $state['bossLive'] -= $spells['poison']['damage'];
        $state['poison']--;
    }
    
    if ($state['shield'] > 0) {
        $state['shield']--;
    }
    
    return $state;
}

function playPlayer($game, $state, $spells) {
    $state['spells'][] = $game;
    $state['mana'] -= $spells[$game]['cost'];
    $state['manaSpent'] += $spells[$game]['cost'];
    
    if (in_array($game, ['magic_missile', 'drain'])) {
        $state['bossLive'] -= $spells[$game]['damage'];
    }
    
    if ($game == 'drain') {
        $state['playerLive'] += $spells['drain']['health'];
    }
    
    if ($game == 'shield') {
        $state['shield'] += $spells['shield']['turns'];
    }
    
    if ($game == 'poison') {
        $state['poison'] += $spells['poison']['turns'];
    }
    
    if ($game == 'recharge') {
        $state['recharge'] += $spells['recharge']['turns'];
    }
    
    return $state;
}

function applySpellBoss($state, $spells) {
    $state['shieldArmor'] = 0;
    
    if ($state['recharge'] > 0) {
        $state['mana'] += $spells['recharge']['mana'];
        $state['recharge']--;
    }
    
    if ($state['poison'] > 0) {
        $state['bossLive'] -= $spells['poison']['damage'];
        $state['poison']--;
    }
    
    if ($state['shield'] > 0) {
        $state['shieldArmor'] = $spells['shield']['bostArmor'];
        $state['shield']--;
    }
    
    return $state;
}

function playBoss($state) {
    $damage = max($state['bossDamage'] - $state['shieldArmor'], 1);
    
    $state['playerLive'] -= $damage;
    
    unset($state['shieldArmor']);
    
    return $state;
}

function cleanStates($states) {
    $uniqueStates = [];
    foreach ($states as $state) {
        $key = implode('-', [$state['playerLive'], $state['mana'], $state['bossLive'], $state['shield'], $state['poison'], $state['recharge']]);
        if (!isset($uniqueStates[$key]) || $uniqueStates[$key]['manaSpent'] > $state['manaSpent']) {
            $uniqueStates[$key] = $state;
        }
    }
    return array_values($uniqueStates);
}