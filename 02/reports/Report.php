<?php

class Report
{
    private const INCREASING = "asc";
    private const DECREASING = "desc";
    public string $direction;

    public function __construct(public array $levels)
    {
    }

    public function checkSafe(): bool
    {
        $this->direction = $this->getDirection();
        $errors = 0;

        foreach ($this->levels as $key => $level) {
            $nextLevel = $this->levels[$key + 1] ?? null;

            if ($nextLevel === null) {
                continue;
            }

            $step = $nextLevel - $level;

            if (!Reports::inRange($step) || !$this->isSameDirection($level, $nextLevel)) {
                $firstCase = $this->levels;
                $secondCase = $this->levels;

                unset($firstCase[$key]);
                unset($secondCase[$key + 1]);

                if ($this->match($firstCase) || $this->match($secondCase)) {
                    return true;
                }

                return false;
            }
        }

        return $errors <= Reports::TOLERANCE;
    }

    private function getDirection(): string
    {
        $directions = [];
        foreach ($this->levels as $key => $level) {
            if (key_exists($key + 1, $this->levels)) {
                $directions[] = $this->getEvolution($level, $this->levels[$key + 1]);
            }
        }

        $directions = array_count_values($directions);
        arsort($directions);

        return array_keys($directions)[0] == self::INCREASING ? self::INCREASING : self::DECREASING;
    }

    private function getEvolution(int $level, int $nextLevel): string
    {
        return $nextLevel > $level ? self::INCREASING : self::DECREASING;
    }

    private function isSameDirection(int $lastLevel, int $level): bool
    {
        return $this->getEvolution($lastLevel, $level) === $this->direction || empty($level);
    }

    private function match(array $list): bool
    {
        $list = array_values($list);
        foreach ( $list as $key => $level) {
            $nextLevel = $list[$key + 1] ?? null;

            if ($nextLevel === null) {
                continue;
            }

            $step = $nextLevel - $level;

            if (Reports::inRange($step) && $this->isSameDirection($level, $nextLevel)) {
                continue;
            }

            return false;
        }

        return true;
    }
}