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
        $lines = explode("\n", $this->getContent());

        return array_map(function (string $line) {
            return Equation::fromString($line);
        }, $lines);
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
