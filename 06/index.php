<?php

require "classes/Map.php";

$map = new Map('06/input.txt');

$map->countGuardDistinctPositions();

$result = $map->getObstaclesPossibilitiesToLoopGuard();

echo "Possibilité des mettre le garde dans une boucle infinie: $result";