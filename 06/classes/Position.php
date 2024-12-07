<?php

class Position
{
    private const EMPTY_SYMBOL = ".";
    private const OCCUPIED_SYMBOL = "#";
    public function __construct(public int $x, public int $y, public bool $isEmpty, public bool $isSecure = true)
    {
    }

    public static function create(int $x, int $y, string $symbol): self
    {
        return match ($symbol) {
            self::EMPTY_SYMBOL => new Position($x, $y, true),
            self::OCCUPIED_SYMBOL => new Position($x, $y, false),
            Guard::SYMBOL => new Position($x, $y, true, false),
        };
    }
}
