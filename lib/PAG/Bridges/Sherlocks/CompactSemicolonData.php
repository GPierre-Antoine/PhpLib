<?php

namespace PAG\Bridges\Sherlocks;


class CompactSemicolonData
{
    private $array;

    public function __construct($array)
    {
        $this->array = $array;
    }

    public function setArray(array $array): void
    {
        $this->array = $array;
    }

    public function append($info): void
    {
        $this->array[] = $info;
    }

    public function __toString(): string
    {
        return $this->computeString();
    }

    public function computeString(): string
    {
        return join(';', array_filter($this->array));
    }
}