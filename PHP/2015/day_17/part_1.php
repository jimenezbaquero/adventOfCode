<?php
$start = microtime(true);

require_once('../../functions.php');

$data = loadFile('input.txt');
//$data = loadFile('test.txt');

$amount = 150;
$recipients = [];

$combinations = [];

foreach ($data as $item) {
    $recipients[] = intval($item);
}

$combinations = calculateRecipients($recipients, $amount);

echo 'Solution: ' . count($combinations) . PHP_EOL;
echo 'Time: ' . number_format(microtime(true) - $start, 2);

function calculateRecipients($recipients, $finishAmount, $actualAmount = 0) {
    if ($actualAmount > $finishAmount) {
        return [];
    }
    
    if ($actualAmount === $finishAmount) {
        return [[]];
    }
    
    if (empty($recipients)) {
        return [];
    }
    
    $result = [];
    
    foreach ($recipients as $key => $recipient) {
        $remaining = array_slice($recipients, $key + 1);
        
        $combinations = calculateRecipients(
            $remaining,
            $finishAmount,
            $actualAmount + $recipient
        );
        foreach ($combinations as $combination) {
            $result[] = [$recipient, ...$combination];
        }
    }
    
    return $result;
}