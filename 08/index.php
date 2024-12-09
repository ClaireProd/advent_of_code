<?php

require "classes/File.php";
require "classes/Map.php";

$map = (new File('08/input.txt'))->parseData();

$result = $map->countAntinodes();

echo "Nombre d'antinodes: $result";