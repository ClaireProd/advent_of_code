<?php

class Guard
{
    private array $map;
    public const SYMBOL = "^";

    public Direction $direction = Direction::UP;

    public function __construct(public int $x, public int $y)
    {
    }

    public function canMove(): bool
    {
        $nextPosition = $this->getNextPosition();

        if ($nextPosition === null) {
            return true;
        }

        return $nextPosition->isEmpty;
    }

    public function moveNext(): void
    {
        $coordinate = match ($this->direction) {
            Direction::UP => ['y', -1],
            Direction::DOWN => ['y', 1],
            Direction::LEFT => ['x', -1],
            Direction::RIGHT => ['x', 1],
        };

        $nextPosition = $this->getNextPosition();

        if ($nextPosition !== null) {
            $nextPosition->isSecure = false;
        }

        $this->{$coordinate[0]} += $coordinate[1];
    }

    public function rotate(): void
    {
        $this->direction = $this->direction->getNext();
    }

    private function getNextPosition(): ?Position
    {
        return match ($this->direction) {
            Direction::UP => $this->map[$this->y - 1][$this->x] ?? null,
            Direction::DOWN => $this->map[$this->y + 1][$this->x] ?? null,
            Direction::LEFT => $this->map[$this->y][$this->x - 1] ?? null,
            Direction::RIGHT => $this->map[$this->y][$this->x + 1] ?? null,
        };
    }

    public function setMap(array $map): void
    {
        if (empty($this->map)) {
            $this->map = $map;
        }
    }

    public function getMap(): array
    {
        return $this->map;
    }

    public function inMap(): bool
    {
        $mapWidth = count($this->map[0]);
        $mapHeight = count($this->map);

        return $this->x >= 0 &&
            $this->x < $mapWidth &&
            $this->y >= 0 &&
            $this->y < $mapHeight;
    }
}
