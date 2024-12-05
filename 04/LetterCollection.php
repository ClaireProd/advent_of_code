<?php

class LetterCollection
{
    public array $columns;
    public array $diagonals;


    private function __construct(public array $rows = [])
    {
        $this->setColumns($this->rows);
        $this->setDiagonals($this->rows);
    }

    public static function fromString(string $data): LetterCollection
    {
        $lines = explode("\n", $data);

        return new self($lines);
    }

    public static function fromArray(array $lines): LetterCollection
    {
        return new self($lines);
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

        $this->columns = array_map(static function ($column) {
            return implode("", $column);
        }, $columns);
    }

    private function setDiagonals(array $rows): void
    {
        $leftToRightDiagonals = [];
        $rightToLeftDiagonals = [];

        foreach ($rows as $rowIndex => $row) {
            $columns = str_split($row, 1);
            foreach ($columns as $colIndex => $column) {
                $leftToRightDiagonals[$rowIndex + $colIndex][] = $column;
                $rightToLeftDiagonals[$rowIndex - $colIndex][] = $column;
            }
        }

        $this->diagonals = array_merge(
            array_map(fn($d) => implode('', $d), $leftToRightDiagonals),
            array_map(fn($d) => implode('', $d), $rightToLeftDiagonals),
        );
    }

    public function countWord(string $word): int
    {
        $reverted = strrev($word);

        $rowsOccurrences = array_reduce($this->rows, function ($carry, $row) use ($word, $reverted) {
            $carry += preg_match_all("/$word/", $row);
            return $carry + preg_match_all("/$reverted/", $row);
        }, 0);

        echo "Lignes: $rowsOccurrences\n";

        $colsOccurrences = array_reduce($this->columns, function ($carry, $row) use ($word, $reverted) {
            $carry += preg_match_all("/$word/", $row);
            return $carry + preg_match_all("/$reverted/", $row);
        }, 0);


        echo "Colonnes: $colsOccurrences\n";

        $diagOccurrences = array_reduce($this->diagonals, function ($carry, $row) use ($word, $reverted) {
            $carry += preg_match_all("/$word/", $row);
            return $carry + preg_match_all("/$reverted/", $row);
        }, 0);

        echo "Diagonales: $diagOccurrences\n";

        return $rowsOccurrences + $colsOccurrences + $diagOccurrences;
    }
}