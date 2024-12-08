<?php

require "classes/File.php";
require "classes/Equation.php";

$equations = (new File('07/input.txt'))->parseData();

$solvableEquations = array_filter($equations, fn($e) => $e->solve());

$result = array_reduce($solvableEquations, fn($sum, $e) => $sum += $e->result);

echo "Somme des équations solvables: $result";