<?php

require "Place.php";

class Map
{
    private const EMPTY_PLACE = '.';
    public array $antennas = [];
    public function __construct(public array $places = [])
    {
    }

    private function calculateInterferences(): void
    {
        foreach ($this->antennas as $antennaGroup) {
            foreach ($antennaGroup as $currentId => $currentAntenna) {
                // Il faut regarder chaque élément avec tous les autres du groupe sauf lui même
                foreach ($antennaGroup as $pairId => $pairAntenna) {
                    if ($currentId !== $pairId) {
                        $this->findAntinodes($currentAntenna, $pairAntenna);
                    }
                }
            }
        }

        $this->output();
    }

    private function findAntinodes(Place $currentAntenna, Place $pairAntenna)
    {
        $gcd = GCD($baseXDelta = $pairAntenna->x - $currentAntenna->x, $baseYDelta = $pairAntenna->y - $currentAntenna->y) !== 0 ?? 1;

        $deltaX = $baseXDelta / $gcd;
        $deltaY = $baseYDelta / $gcd;

        $currentAntenna->isAntinode = true;
        $pairAntenna->isAntinode = true;

        $x = $currentAntenna->x;
        $y = $currentAntenna->y;

        while ($x >= 0 && $y >= 0) {
            $antinode = $this->places[$y][$x] ?? null;

            if ($antinode !== null) {
                $antinode->isAntinode = true;
            } else {
                break;
            }

            $x -= $deltaX;
            $y -= $deltaY;
        }
    }

    public function countAntinodes(): int
    {
        $this->calculateInterferences();

        return array_reduce($this->places, function ($carry, $item) {
            $sum = array_reduce($item, function ($sum, $place) {
                return $sum += (int) $place->isAntinode;
            }, 0);

            return $carry + $sum;
        }, 0);
    }

    public function addPlace(int $x, int $y, string $place): void
    {
        $element = $this->places[$y][$x] = $place === self::EMPTY_PLACE
            ? new Place($x, $y)
            : new Antenna($x, $y, $place);

        if ($element instanceof Antenna) {
            $this->antennas[$element->frequency][] = $element;
        }
    }

    public function output(): void
    {
        foreach ($this->places as $row) {
            foreach ($row as $place) {
                echo ($place instanceof Antenna) ? $place->frequency : ($place->isAntinode ? '#' : '.');
            }

            echo "\n";
        }

        echo "\n";
    }
}

function GCD($a, $b)
{
    if ($b === 0) {
        return $a;
    }
    return GCD($b, $a % $b);
}