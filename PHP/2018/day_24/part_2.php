<?php

$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$solution = 0;
$originalGroups = [];
$inmuneLines = false;
$infectionLines = false;

foreach ($data as $line) {
    $immunes = [];
    $weaks = [];
    $inmune = false;
    $weak = false;
    switch ($line) {
        case '':
            break;
        case 'Immune System:':
            $inmuneLines = true;
            $infectionLines = false;
            $type = 'Immune';
            break;
        case 'Infection:':
            $infectionLines = true;
            $inmuneLines = false;
            $type = 'Infection';
            break;
        default:
            $open = strpos($line, '(');
            $close = strpos($line, ')');
            if($open !== false && $close !== false) {
                $defense = substr($line, $open + 1, $close - $open - 1);
                
                $line = str_replace('(' . $defense . ')', '', $line);
                
                $aux2 = explode('; ', $defense);
                
                foreach ($aux2 as $aux3) {
                    if (str_contains($aux3  , 'weak')) {
                        $weaks = explode(', ', str_replace('weak to ', '', $aux3));
                    }else if(str_contains($aux3  , 'immune')){
                        $immunes = explode(', ', str_replace('immune to ', '', $aux3));
                    }
                }
            }
            $aux1 = explode(' ', str_replace('  ',' ',$line));
            $originalGroups[] = [
                'type' => $type,
                'units' => $aux1[0],
                'hit' => $aux1[4],
                'attack' => $aux1[12],
                'attackType' => $aux1[13],
                'power' => 0,
                'damage' => 0,
                'objetive' => null,
                'multiplier' => 0,
                'initiative' => $aux1[17],
                'weaks' => $weaks,
                'inmunes' => $immunes,
                'selected' => false
            ];
        
    }
}

$boost = 0;

while(true) {
    $groups = $originalGroups;
    foreach (array_keys($groups) as $key) {
        if($groups[$key]['type'] == 'Immune')  {
            $groups[$key]['attack'] += $boost;
        }
    }
    $existEnemy = true;
    while ($existEnemy) {
        $draw = false;
        calculatePower($groups);
        
        uasort($groups, function ($a, $b) {
            $result = $b['power'] <=> $a['power'];
            
            if ($result == 0) {
                $result = $b['initiative'] <=> $a['initiative'];
            }
            
            return $result;
        });
        
        foreach ($groups as $key => $group) {
            $groups[$key]['objetive'] = null;
            $groups[$key]['selected'] = false;
        }
        
        foreach ($groups as $key => $group) {
            findObjetive($group, $key, $groups);
        }
        
        uasort($groups, function ($a, $b) {
            return $b['initiative'] <=> $a['initiative'];
        });
        
        $deathGroups = [];
        $keys = array_keys($groups);
        $totalDeath = 0;
        foreach ($keys as $key) {
            $group = $groups[$key];
            if (isset($deathGroups[$group['objetive']]) || isset($deathGroups[$key])) {
                continue;
            }
            if (is_null($group['objetive'])) {
                continue;
            }
            $deaths = floor($group['units'] * $group['attack'] * $group['multiplier'] / $groups[$group['objetive']]['hit']);
            $totalDeath += $deaths;
            $groups[$group['objetive']]['units'] -= $deaths;
            if ($groups[$group['objetive']]['units'] <= 0) {
                $deathGroups[$group['objetive']] = true;
            }
        }
        if($totalDeath == 0) {
            $draw = true;
            break;
        }
        
        foreach ($deathGroups as $key => $group) {
            unset($groups[$key]);
        }
        
        $immunes = count(array_filter($groups, function ($group) {
            return $group['type'] == 'Immune';
        }));
        $infections = count(array_filter($groups, function ($group) {
            return $group['type'] == 'Infection';
        }));

        if ($immunes == 0 || $infections == 0) {
            $existEnemy = false;
            $solution = array_sum(array_column($groups, 'units'));
        }
    }
    
    if($immunes == 0 || $draw) {
        $boost +=1;
    }else{
        break;
    }
}

echo 'Solution : ' . $solution . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2) . PHP_EOL;
echo 'Memory used: ' . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
echo 'Memory reserved: ' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB' . PHP_EOL;

function calculatePower (&$group){
    foreach ($group as $key => $item) {
        $group[$key]['power'] = $item['units'] * $item['attack'];
    }
}

function findObjetive($group, $key, &$groups){
    $max = 0;
    $objetive = null;
    $multiplier = 1;
    foreach ($groups as $key2 => $group2) {
        if($group2['type'] == $group['type'] || $group2['selected'] != false) {
            continue;
        }
        $typeAttack = $group['attackType'];
        if(in_array($typeAttack, $group2['inmunes'])){
            $mult = 0;
        }else if(in_array($typeAttack, $group2['weaks'])){
            $mult = 2;
        }else{
            $mult = 1;
        }
        $attack = $group['power'] * $mult;
        if($attack > 0) {
            if ($attack > $max) {
                $max = $attack;
                $objetive = $key2;
                $multiplier = $mult;
            } else if ($attack == $max) {
                if ($group2['power'] > $groups[$objetive]['power']) {
                    $objetive = $key2;
                    $multiplier = $mult;
                }else if ($group2['power'] == $groups[$objetive]['power']) {
                    if($group2['initiative'] > $groups[$objetive]['initiative']){
                        $objetive = $key2;
                        $multiplier = $mult;
                    }
                }
            }
        }
    }
    $groups[$key]['objetive'] = $objetive;
    $groups[$key]['multiplier'] = $multiplier;
    if(!is_null($objetive)) {
        $groups[$objetive]['selected'] = true;
    }
}