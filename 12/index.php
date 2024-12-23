<?php

class Plot
{
    public const OUTERMAP = 'inexistant';
    public function __construct(public int $x, public int $y, public string $letter)
    {
        $this->id = "$this->x-$this->y-$this->letter";
    }
}

class Region
{
    public function __construct(public array $plots = [])
    {
    }

    public function attach(Plot $plot): void
    {
        $this->plots[] = $plot;
    }

    public function getArea(): int
    {
        return count($this->plots);
    }

    public function getPerimeter(Map $map): int
    {
        $perimeter = 0;
        foreach ($this->plots as $plot) {
            $perimeter += array_reduce($map->getNeighbors($plot), function ($carry, $p) use($plot) {
                return $carry += ($p->letter ?? Plot::OUTERMAP) === $plot->letter ? 0 : 1;
            }, 0);
        }

        return $perimeter;
    }
}


class Map
{
    public array $plots = [];
    private array $structuredMap = [];
    public array $regions = [];

    public function __construct(private string $path)
    {
        if (!self::exists($path)) {
            throw new InvalidArgumentException("File not found: $this->path");
        }

        $this->initMap();
    }

    private function initMap(): void
    {
        foreach (explode("\n", $this->getContent()) as $y => $line) {
            foreach (str_split($line) as $x => $plot) {
                $plot = new Plot($x, $y, $plot);
                $this->plots[] = $plot;
                $this->structuredMap[$x][$y] = $plot;
            }
        }

        $this->findRegions();
    }

    private function findRegions(): void
    {
        $seen = [];
        $plotIndex = 0;

        while (count($seen) < count($this->plots)) {
            $plot = $this->plots[$plotIndex];

            if (isset($seen[$plot->id])) {
                $plotIndex++;
                continue;
            }

            $region = new Region([]);
            $this->regions[] = $region;

            $neighbors = array_filter($this->getNeighbors($plot), fn($n) => $n !== Plot::OUTERMAP);

            $region->attach($plot);
            $seen[$plot->id] = true;

            foreach ($neighbors as $neighbor) {
                if ($neighbor->letter === $plot->letter && !isset($seen[$neighbor->id])) {
                    $region->attach($neighbor);
                    $seen[$neighbor->id] = true;

                    $this->attachNeighbors($region, $neighbor, $seen);
                }
            }
        }
    }

    private function attachNeighbors(Region $region, Plot $basePlot, array &$seen): void
    {
        $neighbors = array_filter($this->getNeighbors($basePlot), fn($n) => $n !== Plot::OUTERMAP && ($n->letter ?? $n) === $basePlot->letter);

        foreach ($neighbors as $neighbor) {
            if (isset($seen[$neighbor->id])) {
                continue;
            }

            $region->attach($neighbor);
            $seen[$neighbor->id] = true;
            $this->attachNeighbors($region, $neighbor, $seen);
        }
    }

    private static function exists(string $path): bool
    {
        return file_exists($path);
    }

    private function getContent(): string
    {
        return trim(file_get_contents($this->path));
    }

    public function getNeighbors(Plot $plot): array
    {
        return [
            $this->structuredMap[$plot->x - 1][$plot->y] ?? Plot::OUTERMAP,
            $this->structuredMap[$plot->x + 1][$plot->y] ?? Plot::OUTERMAP,
            $this->structuredMap[$plot->x][$plot->y - 1] ?? Plot::OUTERMAP,
            $this->structuredMap[$plot->x][$plot->y + 1] ?? Plot::OUTERMAP,
        ];
    }
}

$map = new Map("12/input.txt");

$price = array_reduce($map->regions, function ($carry, Region $region) use ($map): int {
    return $carry += $region->getPerimeter($map) * $region->getArea();
}, 0);

echo "Prix de la parcelle: $price";