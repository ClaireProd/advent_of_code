<?php

class File
{

    public function __construct(private string $path)
    {
        if (!self::exists($path)) {
            throw new InvalidArgumentException("File not found: $this->path");
        }
    }

    public function parseData(): array
    {
        preg_match_all("/mul\(\d+,\d+\)/", $this->getContent(), $matches);

        return array_map(function ($match) {
            preg_match_all("/\d+/", $match, $matches);

            return [(int) $matches[0][0], (int) $matches[0][1]];
        }, $matches[0] ?? []);
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