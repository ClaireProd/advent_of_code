<?php

require 'LetterCollection.php';
require 'File.php';

$data = (new File("04/input.txt"))->parseData();
echo "Total: " . $data->countWord("XMAS");
