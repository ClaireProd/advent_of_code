<?php

class Person
{
    public function __construct(public array $stones)
    {
    }

    public function blinkEyes()
    {
        $newStoneArrangement = [];

        foreach ($this->stones as $stone) {
            if ($stone->weight === 0) {
                $stone->putOne();
                $newStoneArrangement[] = $stone;
                continue;
            }

            if ($stone->isEven()) {
                $parts = $stone->split();
                foreach ($parts as $part) {
                    $newStoneArrangement[] = $part;
                }
                continue;
            }

            $stone->multiply();
            $newStoneArrangement[] = $stone;
        }

        $this->stones = $newStoneArrangement;
    }

    public function outputStones()
    {
        foreach ($this->stones as $stone) {
            echo "$stone->weight ";
        }

        echo "\n";
    }

    public function countStones(): int
    {
        return count($this->stones);
    }
}