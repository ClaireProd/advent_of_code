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
        $content = $this->getContent();
        $matches = preg_split("/(do\(\)|don't\(\))/", $content, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

        $mulInstructions = [];
        $mulEnabled = true;

        foreach ($matches as $match) {
            if ($match === "do()") {
            $mulEnabled = true;
            } elseif ($match === "don't()") {
            $mulEnabled = false;
            } elseif ($mulEnabled) {
                preg_match_all("/mul\(\d+,\d+\)/", $match, $matches);

                foreach ($matches[0] as $key => $value) {
                    $mulInstructions[] = $value;
                }
            }
        }

        return array_map(function ($match) {
            preg_match_all("/\d+/", $match, $matches);

            return [(int) $matches[0][0], (int) $matches[0][1]];
        }, $mulInstructions ?? []);
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