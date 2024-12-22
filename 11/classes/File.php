<?php

require "Stone.php";

class File
{
    private const STONE_SEPARATOR = " ";

    public function __construct(private string $path)
    {
        if (!self::exists($path)) {
            throw new InvalidArgumentException("File not found: $this->path");
        }
    }

    public function parseData(): array
    {
        $stones = explode(self::STONE_SEPARATOR, $this->getContent());
        
        return array_map(fn (string $stone) => new Stone((int) $stone), $stones);
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
