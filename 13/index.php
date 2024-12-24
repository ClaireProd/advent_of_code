<?php

class File
{
    private const MACHINE_SEPARATOR = "\n\n";

    public function __construct(private string $path)
    {
        if (!self::exists($path)) {
            throw new InvalidArgumentException("File not found: $this->path");
        }
    }

    public function parseData(): array
    {
        $machines = [];

        foreach (explode(self::MACHINE_SEPARATOR, $this->getContent()) as $machine) {
            preg_match_all('/(\d+)/', $machine, $matches);
            $machines[] = array_map('intval', $matches[0]);
        }

        return $machines;
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

$machines = (new File('13/input.txt'))->parseData();

$total = 0;

foreach ($machines as [$aX, $aY, $bX, $bY, $pX, $pY]) {
    $minScore = INF;

    for ($timesA = 0; $timesA < 101; $timesA++) {
        for ($timesB = 0; $timesB < 101; $timesB++) {
            if ($aX * $timesA + $bX * $timesB === $pX && $aY * $timesA + $bY * $timesB === $pY) {
                $minScore = min($minScore, $timesA * 3 + $timesB);
            }
        }
    }

    if ($minScore !== INF) {
        $total += $minScore;
    }
}

echo "Prix minimal: $total";
