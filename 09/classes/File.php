<?php

class File
{
    public function __construct(public ?int $id = null, public int $size)
    {
    }

    public function isEmpty() : bool
    {
        return $this->id === null;
    }
}
