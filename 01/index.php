<?php

require "File.php";

[$firstList, $secondList] = (new File("01/input.txt"))->parseData();

$apparitions = array_count_values($secondList);

$sum = array_reduce($firstList, function ($carry, $item) use ($apparitions) {
    return $carry + $item * ($apparitions[$item] ?? 0);
}, 0);

echo "Résultat final: $sum";