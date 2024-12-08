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
                $this->options[] = (int) ($number . $nextNumber);

                continue;
            }

            $newOptions = [];

            foreach ($this->options as $option) {
                $newOptions[] = $option + $nextNumber;
                $newOptions[] = $option * $nextNumber;
                $newOptions[] = (int) ($option . $nextNumber);
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
