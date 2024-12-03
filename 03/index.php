<?php

require 'File.php';

$file = new File("03/input.txt");

$instructions = $file->parseData();

$sum = array_reduce($instructions, function($carry, $item) {
    return $carry += $item[0] * $item[1];
}, 0);

echo "Somme: $sum";
