<?php

class Guard
{
    public array $map;

    // private Position $placedObstacle;
    public const SYMBOL = "^";

    public array $moveHistory = [];

    public Direction $direction = Direction::UP;

    private int $baseX;

    private int $baseY;

    public function __construct(public int $x, public int $y)
    {
        $this->baseX = $x;
        $this->baseY = $y;
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

        $this->moveHistory[$this->x][$this->y][$this->direction->getKey()] = true;
        $this->{$coordinate[0]} += $coordinate[1];
    }

    public function isLooping(): bool
    {
        return ($this->moveHistory[$this->x][$this->y][$this->direction->getKey()] ?? false) === true;
    }

    public function rotate(): void
    {
        $this->moveHistory[$this->x][$this->y][$this->direction->getKey()] = true;
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

    public function inMap(): bool
    {
        $mapWidth = count($this->map[0]);
        $mapHeight = count($this->map);

        return $this->x >= 0 &&
            $this->x < $mapWidth &&
            $this->y >= 0 &&
            $this->y < $mapHeight;
    }

    public function placeObstacleAtNextStep(array $guardBaseCoordinates): void
    {
        $nextPosition = $this->getNextPosition();

        if ($nextPosition !== null && ($nextPosition->x !== $guardBaseCoordinates[0] || $nextPosition->y !== $guardBaseCoordinates[1])) {
            // $this->placedObstacle = &$nextPosition;
            $nextPosition->isEmpty = false;
            echo "On place l'obstacle en position $nextPosition->x;$nextPosition->y";
        }
    }

    // public function removePlacedObstacle(): void
    // {
    //     if (!empty($this->placedObstacle)) {
    //         $this->placedObstacle->isEmpty = true;
    //     }
    // }
}
