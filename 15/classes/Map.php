<?php

class Map
{
    private const GPS_INDICE = 100;
    public int $robotX;
    public int $robotY;

    private function __construct(public array $map, array $robot)
    {
        $this->robotX = $robot[0];
        $this->robotY = $robot[1];
    }

    public static function fromString(string $input): self
    {
        $map = [];
        $robot = [];

        foreach (explode("\n", $input) as $y => $row) {
            foreach (str_split($row) as $x => $place) {
                $map[$y][$x] = Place::from($place);

                if (Place::from($place) === Place::ROBOT) {
                    $robot = [$x, $y];
                }
            }
        }

        return new self($map, $robot);
    }

    public function moveRobot(Direction $direction): void
    {
        $target = $this->getTarget($this->robotX, $this->robotY, $direction);

        if ($target['type'] === Place::WALL) {
            return;
        }

        if ($target['type'] === Place::EMPTY ) {
            $this->map[$this->robotY][$this->robotX] = Place::EMPTY;

            $this->robotX = $target['x'];
            $this->robotY = $target['y'];

            $this->map[$this->robotY][$this->robotX] = Place::ROBOT;
        }

        if ($target['type'] === Place::BOX) {
            if ($finalTarget = $this->canMoveBoxes($this->robotX, $this->robotY,$direction)) {
                $this->map[$finalTarget['y']][$finalTarget['x']] = Place::BOX;
                $this->map[$this->robotY][$this->robotX] = Place::EMPTY;
                $this->robotX = $target['x'];
                $this->robotY = $target['y'];
                $this->map[$this->robotY][$this->robotX] = Place::ROBOT;
                return;
            }
            return;
        }
    }

    public function canMoveBoxes(int $x, int $y, Direction $direction): ?array
    {
        $target = $this->getTarget($x, $y, $direction);

        if ($target['type'] === Place::WALL) {
            return null;
        }

        if ($target['type'] === Place::BOX) {
            return $this->canMoveBoxes($target['x'], $target['y'], $direction);
        }

        return $target;
    }

    private function getTarget(int $x, int $y, Direction $direction): array
    {
        [$x, $y] = $direction->next($x, $y);
        $place = $this->map[$y][$x] ?? Place::WALL;

        return [
            'x' => $x,
            'y' => $y,
            'type' => $place,
        ];
    }

    public function getGpsSum(): int
    {
        $sum = 0;

        foreach ($this->map as $y => $line) {
            foreach ($line as $x => $place) {
                if ($place === Place::BOX) {
                    $sum += $y * self::GPS_INDICE + $x;
                }
            }
        }

        return $sum;
    }

    public function output()
    {
        foreach ($this->map as $line) {
            foreach ($line as $place) {
                echo $place->value;
            }
            echo "\n";
        }
        echo "\n";
    }
}
