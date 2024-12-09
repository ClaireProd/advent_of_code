<?php

class Place
{
    public bool $isAntinode = false;

    public function __construct(public int $x, public int $y)
    {
    }
}

class Antenna extends Place
{
    public function __construct(public int $x, public int $y, public string $frequency)
    {
        parent::__construct($x, $y);
    }
}