<?php

class StorageUnit
{
    public ?int $id = null;

    public function __construct(?int $id)
    {
        $this->id = $id;
    }

    public function isEmpty() : bool
    {
        return $this->id === null;
    }

    public function switchWith(StorageUnit $destination): void
    {
        $baseId = $this->id;

        $this->id = $destination->id;

        $destination->id = $baseId;
    }
}
