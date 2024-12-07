<?php

require "Guard.php";
require "Direction.php";
require "Position.php";

class Map
{
    private Guard $guard;
    public array $positions;
    public function __construct(private string $path)
    {
        if (!self::exists($path)) {
            throw new InvalidArgumentException("File not found: $this->path");
        }

        $this->initMap();
    }

    private function initMap(): void
    {
        $rows = explode("\n", $this->getContent());

        foreach ($rows as $rowIndex => $row) {
            $positions = str_split($row);
            foreach ($positions as $colIndex => $position) {
                $place = $this->positions[$rowIndex][$colIndex] = Position::create($colIndex, $rowIndex, $position);

                if ($position === Guard::SYMBOL) {
                    $this->guard = new Guard($colIndex, $rowIndex);
                    $place->isSecure = false;
                }
            }
        }

        $this->guard->setMap($this->positions);
    }

    public function countGuardDistinctPositions(): int
    {
        $this->simulateGuardMoves();

        return array_reduce($this->positions, function (int $carry, array $row) {
            return $carry += array_reduce($row, function (int $sum, Position $position) {
                return $sum += $position->isSecure ? 0 : 1;
            }, 0);
        }, 0);
    }

    private function simulateGuardMoves(): void
    {
        while ($this->guard->inMap()) {
            if ($this->guard->canMove()) {
                $this->guard->moveNext();
            } else {
                $this->guard->rotate();
            }
        }

        $this->positions = $this->guard->getMap();
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