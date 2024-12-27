<?php

class Map
{
    private const EMPTY = false;

    public function __construct(
        public array $map,
        public int $x,
        public int $y,
        public int $endX,
        public int $endY
    ) {
    }

    public function getLowestScore(): int
    {
        $bestScore = INF;
        $queue = new SplPriorityQueue();
        $queue->insert([$this->x, $this->y, 0, 'r'], 0);
        $visited = [];

        while (!$queue->isEmpty()) {
            [$currentX, $currentY, $currentScore, $currentDirection] = $queue->extract();

            // Skip if already visited with a better or equal score
            $stateKey = "$currentX,$currentY";
            if (isset($visited[$stateKey]) && $visited[$stateKey] <= $currentScore) {
                continue;
            }
            $visited[$stateKey] = $currentScore;

            // Check if reached the end
            if ($currentX === $this->endX && $currentY === $this->endY) {
                $bestScore = min($bestScore, $currentScore);
                continue;
            }

            // Add valid moves to the queue
            foreach ($this->getMoveOptions($currentX, $currentY) as $option) {
                $newScore = $currentScore + ($option['direction'] === $currentDirection ? 1 : 1001);
                $queue->insert([$option['x'], $option['y'], $newScore, $option['direction']], -$newScore);
            }
        }

        return $bestScore === INF ? -1 : $bestScore;
    }

    private function getMoveOptions(int $x, int $y): array
    {
        $directions = [
            'b' => [$x, $y + 1],
            't' => [$x, $y - 1],
            'r' => [$x + 1, $y],
            'l' => [$x - 1, $y],
        ];

        $options = [];
        foreach ($directions as $direction => [$nx, $ny]) {
            if (isset($this->map[$ny][$nx]) && $this->map[$ny][$nx] === self::EMPTY) {
                $options[] = ['x' => $nx, 'y' => $ny, 'direction' => $direction];
            }
        }

        return $options;
    }
}
