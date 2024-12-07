<?php

enum Direction
{
    case UP;
    case DOWN;
    case LEFT;
    case RIGHT;

    public function getNext()
    {
        return match ($this) {
            self::UP => self::RIGHT,
            self::DOWN => self::LEFT,
            self::LEFT => self::UP,
            self::RIGHT => self::DOWN,
        };
    }
}
