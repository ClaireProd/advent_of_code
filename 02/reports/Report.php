<?php

class Report
{
    private const INCREASING = "asc";
    private const DECREASING = "desc";
    public string $direction;
    public Status $status;

    public function __construct(public array $levels)
    {
        $this->status = Status::PENDING;
    }

    public function checkSafe(): bool
    {
        foreach ($this->levels as $key => $level) {
            $lastLevel = $this->levels[$key - 1] ?? null;
            $nextLevel = $this->levels[$key + 1] ?? null;

            if (is_null($lastLevel)) {
                $this->direction = $this->getDirection($level, $nextLevel);
                continue;
            }

            $step = $level - $lastLevel;

            if (Reports::inRange($step) && ($this->isSameDirection($level, $nextLevel))) {
                $this->setSafeIfPossible();
                continue;
            }

            $this->status = Status::UNSAFE;
        }

        return $this->status === Status::SAFE;
    }

    private function setSafeIfPossible(): void
    {
        $this->status = $this->status === Status::UNSAFE ? Status::UNSAFE : Status::SAFE;
    }

    private function getDirection($level, $nextLevel): string
    {
        return $nextLevel > $level ? self::INCREASING : self::DECREASING;
    }

    private function isSameDirection($level, $nextLevel): bool
    {
        return $this->getDirection($level, $nextLevel) === $this->direction || empty($nextLevel);
    }
}

enum Status
{
    case SAFE;
    case UNSAFE;
    case PENDING;
}