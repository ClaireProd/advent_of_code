<?php

require 'File.php';
require 'reports/Reports.php';

$file = new File("02/input.txt");

$reports = new Reports($file->parseData());

$result = count($reports->safe());

echo "Rapport sûrs: $result";