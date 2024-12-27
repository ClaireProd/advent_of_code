<?php

require "classes/Map.php";
require "classes/File.php";

// Usage
$map = (new File('16/input.txt'))->parseData();
$result = $map->getLowestScore();

echo "Score le plus faible possible: $result\n";

