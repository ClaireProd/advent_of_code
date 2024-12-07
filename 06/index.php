<?php

require "classes/Map.php";

$map = new Map('06/input.txt');

$result = $map->countGuardDistinctPositions();

echo "Positions visitées par le garde: $result / " . (count($map->positions) * count($map->positions[0]));