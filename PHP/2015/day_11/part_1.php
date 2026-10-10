<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$password = $data[0];

do {
    $password = preparePassword($password);
    $password = calculatePassword($password);
} while (!isCorrect($password));

echo 'Solution: ' . $password . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2);

function preparePassword(string $password): string {
    $newPassword = '';
    $find = false;
    for ($i = 0; $i < strlen($password); $i++) {
        switch($password[$i]) {
            case 'i':
                $newPassword .= 'j';
                $find = true;
                break;
            case 'l':
                $newPassword .= 'm';
                $find = true;
                break;
            case 'o':
                $newPassword .= 'p';
                $find = true;
                break;
        }
        if($find) {
            for ($j = $i + 1; $j < strlen($password); $j++) {
                $newPassword .= 'a';
            }
            return $newPassword;
        }else{
            $newPassword .= $password[$i];
        }
    }
    return $newPassword;
}

function calculatePassword($oldPassword) {
    $password = '';
    $letters = [
        'a',
        'b',
        'c',
        'd',
        'e',
        'f',
        'g',
        'h',
        'i',
        'j',
        'k',
        'l',
        'm',
        'n',
        'o',
        'p',
        'q',
        'r',
        's',
        't',
        'v',
        'w',
        'x',
        'y',
        'z'
    ];
    
    $arrayOldPassword = str_split($oldPassword);
    
    $rebase = true;
    for ($i = count($arrayOldPassword) - 1; $i >= 0; $i--) {
        $letter = $arrayOldPassword[$i];
        if ($rebase) {
            $rebase = false;
            $position = array_search($letter, $letters);
            if (($position + 1) % count($letters) == 0) {
                $rebase = true;
                $password = $letters[0] . $password;
            } else {
                $password = $letters[$position + 1] . $password;
            }
        } else {
            $password = $letter . $password;
        }
    }
    return $password;
}

function isCorrect($password) {
    if (!hasCrecientBlock($password)) {
        return false;
    }
    if (hasInvalidCharacters($password, ['i', 'o', 'l'])) {
        return false;
    }
    if (!hasTwoPairs($password)) {
        return false;
    }
    return true;
}

function hasCrecientBlock($password) {
    $letters = [
        'a',
        'b',
        'c',
        'd',
        'e',
        'f',
        'g',
        'h',
        'i',
        'j',
        'k',
        'l',
        'm',
        'n',
        'o',
        'p',
        'q',
        'r',
        's',
        't',
        'v',
        'w',
        'x',
        'y',
        'z'
    ];
    
    $pos0 = array_search($password[0], $letters);
    $pos1 = array_search($password[1], $letters);
    $pos2 = array_search($password[2], $letters);
    
    for ($i = 3; $i < strlen($password) ; $i++) {
        if ($pos1 - $pos0 == 1 && $pos2 - $pos1 == 1) {
            return true;
        } else {
            $pos0 = $pos1;
            $pos1 = $pos2;
            $pos2 = array_search($password[$i], $letters);;
        }
    }
    
    if ($pos1 - $pos0 == 1 && $pos2 - $pos1 == 1) {
        return true;
    }
    return false;
}

function hasInvalidCharacters($password, $characters) {
    foreach ($characters as $character) {
        if (str_contains($password, $character)) {
            return true;
        }
    }
    return false;
}

function hasTwoPairs($password) {
    $doubles = 0;
    $letterDouble = null;
    for ($i = 0; $i < strlen($password) - 1; $i++) {
        if($password[$i] == $password[$i+1] && $password[$i] != $letterDouble) {
            $letterDouble = $password[$i];
            $doubles++;
            $i++;
        }
        if($doubles == 2){
            return true;
        }
    }
    return false;
}