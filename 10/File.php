<?php

class Map
{
    public array $places = [];
    public array $startPoints = [];

    public array $endPoints = [];

    private const MIN_HEIGHT = 0;
    private const MAX_HEIGHT = 9;
    private const STEP = 1;
    public function addPlace(int $x, int $y, int $height)
    {
        $this->places[$y][$x] = $height;

        if ($height === self::MIN_HEIGHT) {
            $this->startPoints[] = ['x' => $x, 'y' => $y, 'height' => self::MIN_HEIGHT];
        }
    }

    public function countItineraries(): int
    {
        foreach ($this->startPoints as $index => $place) {
            $possibilities = array_filter(
                $this->getNeighbors($place['x'], $place['y']),
                fn($n) => $n['height'] === self::MIN_HEIGHT + self::STEP
            );

            if (count($possibilities) === 0) {
                continue;
            }

            for ($h = self::MIN_HEIGHT + self::STEP; $h < self::MAX_HEIGHT; $h++) {
                $newPoss = [];

                foreach ($possibilities as $poss) {
                    $neighbors = array_filter(
                        $this->getNeighbors($poss['x'], $poss['y']),
                        fn($n) => $n['height'] === $h + self::STEP
                    );


                    $newPoss = array_map('unserialize', array_unique(array_map('serialize', array_merge($newPoss, $neighbors))));
                }

                $possibilities = $newPoss;
            }

            $this->endPoints[$index] = $possibilities;
        }

        return array_reduce($this->endPoints, function ($carry, $point) {
            return $carry + count($point);
        }, 0);
    }

    public function getNeighbors(int $x, int $y): array
    {
        $neighbors = [
            ['x' => $x, 'y' => $y - 1, 'height' => $this->places[$y - 1][$x] ?? null],
            ['x' => $x, 'y' => $y + 1, 'height' => $this->places[$y + 1][$x] ?? null],
            ['x' => $x - 1, 'y' => $y, 'height' => $this->places[$y][$x - 1] ?? null],
            ['x' => $x + 1, 'y' => $y, 'height' => $this->places[$y][$x + 1] ?? null],

        ];

        return array_filter($neighbors, function ($place) {
            return $place['height'] !== null;
        });
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

$map = (new File('10/input.txt'))->parseData();

$result = $map->countItineraries();

echo "Itinéraires: $result\n";
