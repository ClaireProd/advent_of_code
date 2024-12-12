<?php

require "File.php";

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
            $data[] = new File($isEmpty ? null : $id, $value);

            if ($isEmpty) {
                $id++;
            }

            $isEmpty = !$isEmpty;
        }

        return new self($data);
    }

    public function optimizeSpace(): void
    {
        $baseIndice = 1;
        $processingIndex = count($this->storage) - 1;

        while ($processingIndex >= 0) {
            /** @var File $file */

            $lastNonEmptyFileIndex = count($this->storage) - $baseIndice;

            if ($this->storage[$lastNonEmptyFileIndex] ?? null === null) {
                $processingIndex--;
                continue;
            }

            while ($lastNonEmptyFileIndex < $processingIndex && $this->storage[$lastNonEmptyFileIndex]->isEmpty()) {
                if ($lastNonEmptyFileIndex < 0) {
                    break;
                }

                $lastNonEmptyFileIndex--;
            }

            // Ici on a récupéré le dernier élément

            foreach ($this->storage as $key => $file) {
                if ($key <= $lastNonEmptyFileIndex && $file->isEmpty() && $file->size < $this->storage[$processingIndex]->size) {
                    echo "On va inverser\n";
                }
            }

            $baseIndice += $this->storage[$processingIndex]->size;

            echo "Élément de fin "  . $this->storage[$processingIndex]->id ."\n";

            $processingIndex--;
        }
    }

    public function calculateChecksum(): int
    {
        $sum = 0;

        foreach ($this->storage as $position => $unit) {
            if (!$unit->isEmpty()) {
                $sum += $position * $unit->id * $unit->size;
            }
        }

        return $sum;
    }

    public function output(): void
    {
        foreach ($this->storage as $unit) {
            echo str_repeat($unit->isEmpty() ? "." : $unit->id, $unit->size);
        }

        echo "\n";
    }
}
