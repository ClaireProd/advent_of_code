<?php

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
