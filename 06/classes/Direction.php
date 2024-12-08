<?php

enum Direction
{
    case UP;
    case DOWN;
    case LEFT;
    case RIGHT;

    public function getNext(): Direction
    {
        return match ($this) {
            self::UP => self::RIGHT,
            self::DOWN => self::LEFT,
            self::LEFT => self::UP,
            self::RIGHT => self::DOWN,
        };
    }

    public function getKey(): string
    {
        return match($this) {
            self::UP => "up",
            self::DOWN => "down",
            self::LEFT => "left",
            self::RIGHT => 'right',
        };
    }
}
