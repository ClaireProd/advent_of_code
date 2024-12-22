<?php

class Stone
{
    private const MULTIPLICATION_INDICE = 2024;
    private const MIN_WEIGHT = 1;

    public function __construct(public int $weight)
    {
    }

    public function split(): array
    {
        $weightStr = (string)$this->weight;
        $length = strlen($weightStr);
        $halfLength = $length >> 1; // Bitwise shift for division by 2

        $firstPart = (int)substr($weightStr, 0, $halfLength);
        $secondPart = (int)substr($weightStr, $halfLength);

        return [new Stone($firstPart), new Stone($secondPart)];
    }

    public function multiply(): void
    {
        $this->weight *= self::MULTIPLICATION_INDICE;
    }

    public function isEven(): bool
    {
        return (int)log10($this->weight) % 2 === 1;
    }

    public function putOne(): void
    {
        $this->weight = self::MIN_WEIGHT;
    }
}
