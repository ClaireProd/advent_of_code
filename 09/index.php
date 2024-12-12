<?php

require "classes/InputReader.php";
require "classes/Disk.php";

$disk = (new InputReader('09/input-test.txt'))->parseData();

$disk->output();

$disk->optimizeSpace();

$disk->output();

$result = $disk->calculateChecksum();

echo "Checksum du disque: $result\n";
