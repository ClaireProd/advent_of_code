<?php

class Equation
{
    private const RESULT_SEPARATOR = ":";
    private const NUMBERS_SEPARATOR = " ";

    public array $options = [];
    public function __construct(public array $numbers, public int $result)
    {
    }

    public static function fromString(string $equation): self
    {
        $equation = explode(self::RESULT_SEPARATOR, $equation);
        $numbers = array_map('intval', explode(self::NUMBERS_SEPARATOR, trim($equation[1])));

        return new self($numbers, $equation[0]);
    }

    public function solve(): bool
    {
        foreach ($this->numbers as $index => $number) {
            $nextNumber = $this->numbers[$index + 1] ?? null;

            if ($nextNumber === null) {
                continue;
            }

            if (empty($this->options)) {
                // First loop, put base values using 
                $this->options[] = $number + $nextNumber;
                $this->options[] = $number * $nextNumber;
                continue;
            }

            $newOptions = [];

            foreach ($this->options as $option) {
                $sum = $option + $nextNumber;
                $multiplication = $option * $nextNumber;

                $newOptions[] = $sum;
                $newOptions[] = $multiplication;
            }

            $this->options = $newOptions;
        }

        // On retourne la solution sur la base des options calculées précédemment
        return $this->optionsContainsSolution();
    }

    private function optionsContainsSolution(): bool
    {
        return in_array($this->result, $this->options);
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

$equations = (new File('07/input.txt'))->parseData();

$solvableEquations = array_filter($equations, fn($e) => $e->solve());

$result = array_reduce($solvableEquations, fn($sum, $e) => $sum += $e->result);

echo "Somme des équations solvables: " . $result . "\n";