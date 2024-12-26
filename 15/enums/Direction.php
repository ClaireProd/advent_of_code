<?php

enum Direction: string
{
    case UP = '^';
    case DOWN = 'v';
    case LEFT = '<';
    case RIGHT = '>';

    public function next(int $x, int $y): array
    {
        return match ($this) {
            self::UP => [$x, $y - 1],
            self::DOWN => [$x, $y + 1],
            self::LEFT => [$x - 1, $y],
            self::RIGHT => [$x + 1, $y],
        };
    }
}
