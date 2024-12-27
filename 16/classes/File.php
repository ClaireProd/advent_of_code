<?php

class File
{
    private const WALL = "#";
    public const START = "S";
    public const END = "E";

    public function __construct(private string $path)
    {
        if (!self::exists($path)) {
            throw new InvalidArgumentException("File not found: $this->path");
        }
    }

    public function parseData(): Map
    {
        $map = [];
        $startX = $startY = $endX = $endY = null;

        foreach (explode("\n", $this->getContent()) as $y => $line) {
            foreach (str_split($line) as $x => $place) {
                $map[$y][$x] = $place === self::WALL;

                if ($place === self::START) {
                    $startX = $x;
                    $startY = $y;
                } elseif ($place === self::END) {
                    $endX = $x;
                    $endY = $y;
                }
            }
        }

        if ($startX === null || $startY === null || $endX === null || $endY === null) {
            throw new RuntimeException("Start or End position not found in the map");
        }

        return new Map($map, $startX, $startY, $endX, $endY);
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
