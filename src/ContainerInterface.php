<?php

namespace Oktokod\DI;

interface ContainerInterface
{
    /**
     * Get item from container, or try to resolve it if it doesn't exists.
     *
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed;

    /**
     * Check if item exists in container.
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * Set container item.
     *
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function set(string $key, mixed $value): static;

    /**
     * Set multiple container items.
     *
     * @param array $definitions
     * @return $this
     */
    public function load(array $definitions): static;

    /**
     * Resolve dependencies and invoke method from class.
     *
     * @param string $class
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function invoke(string $class, string $method, array $parameters = []): mixed;
}