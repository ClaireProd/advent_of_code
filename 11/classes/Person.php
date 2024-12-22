<?php

class Person
{
    private array $cache = [];
    public function __construct(public array $stones)
    {
    }

    public function blinkEyes()
    {
        $newStoneArrangement = [];

        foreach ($this->stones as $stone) {
            if ($this->cache[$stone->weight] ?? null === null) {
                if ($stone->weight === 0) {
                    $stone->putOne();
                    $newStoneArrangement[] = $stone;
                    continue;
                }

                if ($stone->isEven()) {
                    $data = $stone->split();
                    array_push($newStoneArrangement, ...$data);
                    $this->cache[$stone->weight] = $data;
                    continue;
                }

                $stone->multiply();
                $newStoneArrangement[] = $stone;
                $this->cache[$stone->weight] = $stone->weight;
                continue;
            }

            $data = $this->cache[$stone->weight];

            if (is_array($data)) {
                array_push($newStoneArrangement, ...$stone->split());
                continue;
            }

            $stone->weight = $data;
            $newStoneArrangement[] = $stone;

        }

        // var_dump($newStoneArrangement);

        $this->stones = $newStoneArrangement;
    }

    public function outputStones()
    {
        echo implode(' ', array_map(fn($stone) => $stone->weight, $this->stones)) . "\n";
    }

    public function countStones(): int
    {
        return count($this->stones);
    }
}
