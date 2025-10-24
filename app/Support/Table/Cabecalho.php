<?php

namespace App\Support\Table;

readonly class Cabecalho
{
    public function __construct(public string $key, public string $label){}

    public static function make(string $key, string $label): self
    {
        return new self($key, $label);
    }
}
