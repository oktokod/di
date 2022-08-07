<?php

namespace Oktokod\DI;

interface ContainerInterface
{
    /**
     * Get item from container.
     *
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed;

    /**
     * Check if container has item.
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * Add item to container.
     *
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function set(string $key, mixed $value): static;

    /**
     * Add multiple items to container.
     *
     * @param array $items
     * @return $this
     */
    public function load(array $items): static;

    /**
     * Invoke class method.
     *
     * @param string $class
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function invoke(string $class, string $method, array $parameters = []): mixed;
}