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
        $this->positions = [];
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

        $this->guard->map = $this->positions;
    }

    public function getObstaclesPossibilitiesToLoopGuard(): int
    {
        $loopingItineraries = 0;
        // $totalSteps = $this->getTotalSteps();

        foreach ($this->positions as $y => $row) {
            foreach ($row as $x => $position) {
                if (!$position->isSecure) {
                    $this->initMap();
                    $this->guard->map[$y][$x]->placeObstacle();

                    while ($this->guard->inMap()) {
                        if ($this->guard->canMove()) {
                            $this->guard->moveNext();
                        } else {
                            $this->guard->rotate();
                        }

                        if ($this->guard->isLooping()) {
                            $loopingItineraries++;
                            break;
                        }
                    }
                }
            }
        }

        return $loopingItineraries;
    }

    public function countGuardDistinctPositions(): int
    {
        $this->simulateGuardMoves();

        return array_reduce($this->guard->map, function (int $carry, array $row) {
            return $carry += array_reduce($row, function (int $sum, Position $position) {
                return $sum += $position->isSecure ? 0 : 1;
            }, 0);
        }, 0);
    }

    private function simulateGuardMoves(): void
    {
        $guardBasePosition = [$this->guard->x, $this->guard->y];
        $guardBaseMap = $this->guard->map;

        while ($this->guard->inMap()) {
            if ($this->guard->canMove()) {
                $this->guard->moveNext();
            } else {
                $this->guard->rotate();
            }
        }

        $this->guard->x = $guardBasePosition[0];
        $this->guard->y = $guardBasePosition[1];
        $this->guard->map = $guardBaseMap;
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