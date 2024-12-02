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

        $firstList = [];
        $secondList = [];

        foreach ($lines as $line) {
            $result = explode('   ', $line);
            if (count($result) < 2) {
                error_log("Invalid line format: $line");
                continue;
            }
            $firstList[] = (int) $result[0];
            $secondList[] = (int) $result[1];
        }

        sort($firstList);
        sort($secondList);

        return [$firstList, $secondList];
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