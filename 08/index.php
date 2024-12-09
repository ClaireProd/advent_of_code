<?php

class Place
{
    public bool $isAntinode = false;

    public function __construct(public int $x, public int $y)
    {
    }
}

class Antenna extends Place
{
    public function __construct(public int $x, public int $y, public string $frequency)
    {
        parent::__construct($x, $y);
    }
}

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
                        $deltaX = $pairAntenna->x - $currentAntenna->x;
                        $deltaY = $pairAntenna->y - $currentAntenna->y;

                        $antinodeX = $currentAntenna->x + ($deltaX * -1);
                        $antinodeY = $currentAntenna->y + ($deltaY * -1);

                        $antinode = $this->places[$antinodeY][$antinodeX] ?? null;

                        if ($antinode !== null) {
                            $antinode->isAntinode = true;
                        }
                    }
                }
            }
        }

        $this->output();
    }

    public function countAntinodes(): int
    {
        $this->calculateInterferences();

        return array_reduce($this->places, function ($carry, $item) {
            $sum = array_reduce($item, function ($sum, $place) {
                return $sum += (int) $place->isAntinode;
            }, 0);

            return $carry + $sum;
        },0);
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

class File
{
    public function __construct(private string $path)
    {
        if (!self::exists($path)) {
            throw new InvalidArgumentException("File not found: $this->path");
        }
    }

    public function parseData(): Map
    {
        $lines = explode("\n", $this->getContent());
        $map = new Map();


        foreach ($lines as $y => $line) {
            $columns = str_split($line);
            foreach ($columns as $x => $place) {
                $map->addPlace($x, $y, $place);
            }
        }

        return $map;
    }

    private static function exists(string $path): bool
    {
        return file_exists($path);
    }

    private function getContent(): string
    {
        return trim(file_get_contents($this->path));
    }
}

$map = (new File('08/input.txt'))->parseData();

$result = $map->countAntinodes();

echo "Nombre d'antinodes: $result";