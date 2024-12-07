<?php

require "classes/PrintQueue.php";
require "classes/Update.php";
require "classes/Rule.php";

$queue = PrintQueue::create("05/input.txt")->parseData();

$result = array_reduce($queue->updates, function ($carry, $update) use($queue) {
    if (!$update->isValid($queue->rules)) {
        $update->orderPages($queue->buildDependencies());
        
        return $carry += $update->getCenterItem();
    }

    return $carry;
}, 0);

echo "Sortie: $result";

