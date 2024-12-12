<?php

class InputReader
{
    public function __construct(private string $path)
    {
        if (!file_exists($path)) {
            throw new InvalidArgumentException("File not found: $this->path");
        }
    }

    public function parseData(): Disk
    {
        return Disk::fromString(trim(file_get_contents($this->path)));
    }
}
