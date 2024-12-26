<?php

require "classes/Map.php";
require "enums/Direction.php";
require "enums/Place.php";

const INPUT_PATH = "15/input.txt";

[$map, $movements] = explode("\n\n", trim(file_get_contents(INPUT_PATH)));

$movements = array_map(
    fn(string $m): Direction => Direction::from($m),
    str_split(str_replace("\n", '', $movements))
);

$map = Map::fromString($map);

foreach ($movements as $movement) {
    $map->moveRobot($movement);
}

$result = $map->getGpsSum();

echo "Somme des valeurs GPS: $result\n";
