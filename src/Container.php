<?php

namespace Oktokod\DI;

class Container implements ContainerInterface
{
    protected array $pool = [];

    /**
     * @inheritDoc
     */
    public function get(string $key): mixed
    {
        $value = null;

        if ($this->has($key)) {
            $value = $this->pool[$value];
        }

        if ($value instanceof \Closure) {
            $value = $value($this);
            $this->set($key, $value);
        }

        if (!$value) {
            $value = $this->resolve($key);
        }

        return $value;
    }

    /**
     * @inheritDoc
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->pool);
    }

    /**
     * @inheritDoc
     */
    public function set(string $key, mixed $value): static
    {
        $this->pool[$key] = $value;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function load(array $definitions): static
    {
        $this->pool = array_merge($this->pool, $definitions);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function invoke(string $class, string $method, array $parameters = []): mixed
    {
        // TODO: Implement invoke() method.
        return null;
    }

    protected function resolve(string $key): object
    {
        try {
            $reflection = new \ReflectionClass($key);
            $constructor = $reflection->getConstructor();

            if (!$constructor) {
                return $reflection->newInstance();
            }

            $parameters = array_map(function (\ReflectionParameter $param) {
                return $this->get($param->getType()->getName());
            }, $constructor->getParameters());

            return $reflection->newInstance(...$parameters);
        } catch (\ReflectionException $exception) {
            // TODO: Handle ReflectionException
            throw new \Exception('Unhandled ReflectionException', $exception);
        }
    }
}