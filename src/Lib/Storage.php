<?php

namespace Oktokod\DI\Lib;

/**
 * @internal Used internally by oktokod/di library.
 */
class Storage
{
    private array $items = [];

    public function get(string $key): mixed
    {
        return $this->items[$key];
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->items);
    }

    public function set(string $key, mixed $value): self
    {
        $this->items[$key] = $value;

        return $this;
    }
}