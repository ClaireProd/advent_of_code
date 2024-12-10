<?php

require "StorageUnit.php";

class Disk
{
    public function __construct(public array $storage)
    {
    }

    public static function fromString(string $storage): self
    {
        $data = [];
        $isEmpty = false;
        $id = 0;

        foreach (str_split($storage) as $value) {
            for ($i = 0; $i < $value; $i++) {
                $data[] = new StorageUnit($isEmpty ? null : $id);
            }

            if ($isEmpty) {
                $id++;
            }
            $isEmpty = !$isEmpty;
        }

        return new self($data);
    }

    public function optimizeSpace(): void
    {
        $lastNonEmptyIndex = count($this->storage) - 1;

        foreach ($this->storage as $index => $unit) {
            if ($unit->isEmpty()) {
                while ($lastNonEmptyIndex > $index && $this->storage[$lastNonEmptyIndex]->isEmpty()) {
                    $lastNonEmptyIndex--;
                }

                if ($lastNonEmptyIndex > $index) {
                    $unit->switchWith($this->storage[$lastNonEmptyIndex]);
                    $lastNonEmptyIndex--;
                } else {
                    break;
                }
            }
        }
    }

    public function calculateChecksum(): int
    {
        $sum = 0;

        foreach ($this->storage as $position => $unit) {
            if (!$unit->isEmpty()) {
                $sum += $position * $unit->id;
            }
        }

        return $sum;
    }

    public function output(): void
    {
        foreach ($this->storage as $unit) {
            echo $unit->isEmpty() ? "." : $unit->id;
        }

        echo "\n";
    }
}
