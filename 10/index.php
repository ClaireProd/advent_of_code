<?php

require "classes/File.php";
require "classes/Map.php";

$map = (new File('10/input.txt'))->parseData();

$result = $map->countItineraries();

echo "Itinéraires: $result";
