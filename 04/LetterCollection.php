<?php

class LetterCollection
{
    public array $columns = [];

    private function __construct(public array $rows = [])
    {
        $this->setColumns($this->rows);
    }

    public static function fromString(string $content): LetterCollection
    {
        return new self(explode("\n", $content));
    }

    private function setColumns(array $rows): void
    {
        $columns = [];

        foreach ($rows as $row) {
            $chars = str_split($row, 1);

            foreach ($chars as $key => $char) {
                $columns[$key][] = $char;
            }
        }

        $this->columns = $columns;
    }

    public function findX(): int
    {
        $occurrences = 0;

        foreach ($this->rows as $rowIndex => $row) {
            $chars = str_split($row, 1);

            foreach ($chars as $colIndex => $char) {
                if ($char === "A") {
                    $neighbors = $this->getNeighbors($rowIndex, $colIndex);

                    if ($this->isValidPattern($neighbors)) {
                        $occurrences++;
                    }
                }
            }
        }

        return $occurrences;
    }

    private function getNeighbors(int $rowIndex, int $colIndex): array
    {
        return [
            'topLeft' => $this->columns[$colIndex - 1][$rowIndex - 1] ?? "",
            'topRight' => $this->columns[$colIndex + 1][$rowIndex - 1] ?? "",
            'bottomLeft' => $this->columns[$colIndex - 1][$rowIndex + 1] ?? "",
            'bottomRight' => $this->columns[$colIndex + 1][$rowIndex + 1] ?? ""
        ];
    }

    private function isValidPattern(array $neighbors): bool
    {
        return in_array($neighbors['topLeft'] . $neighbors['bottomRight'], ['MS', 'SM']) &&
            in_array($neighbors['topRight'] . $neighbors['bottomLeft'], ['MS', 'SM']);

    }
}