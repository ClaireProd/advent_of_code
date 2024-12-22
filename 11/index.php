<?php

require "classes/File.php";
require "classes/Person.php";

$stones = (new File("11/input-test.txt"))->parseData();

$person = new Person($stones);

for ($i = 0; $i < 25; $i++) {
    $person->blinkEyes();
}

echo "Nombre de pierres: {$person->countStones()}";


