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
        $stones = str_split(strval($this->weight), strlen($this->weight) / 2);

        return array_map(fn(string $s) => new Stone(intval($s)), $stones);
    }

    public function multiply(): void
    {
        $this->weight *= self::MULTIPLICATION_INDICE;
    }

    public function isEven(): bool
    {
        return strlen(strval($this->weight)) % 2 === 0;
    }

    public function putOne(): void
    {
        $this->weight = self::MIN_WEIGHT;
    }
}