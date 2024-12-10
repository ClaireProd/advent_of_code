<?php

require "classes/File.php";
require "classes/Disk.php";

$disk = (new File('09/input.txt'))->parseData();

$disk->optimizeSpace();

$result = $disk->calculateChecksum();

echo "Checksum du disque: $result\n";
