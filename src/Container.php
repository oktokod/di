<?php

namespace Oktokod\DI;

use Oktokod\DI\Lib\Resolver;
use Oktokod\DI\Lib\Storage;

class Container implements ContainerInterface
{
    protected Storage $storage;
    protected Resolver $resolver;

    public function __construct()
    {
        $this->storage = new Storage();
        $this->resolver = new Resolver($this);
    }

    /**
     * @inheritDoc
     * @throws ContainerException
     */
    public function get(string $key): mixed
    {
        if ($this->storage->has($key)) {
            return $this->storage->get($key);
        }

        try {
            return $this->resolver->resolveClass($key);
        } catch (\Exception $e) {
            throw new ContainerException("Can't resolve '$key'. {$e->getMessage()}");
        }
    }

    /**
     * @inheritDoc
     */
    public function has(string $key): bool
    {
        return $this->storage->has($key);
    }

    /**
     * @inheritDoc
     */
    public function set(string $key, mixed $value): static
    {
        $this->storage->set($key, $value);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function load(array $items): static
    {
        foreach ($items as $key => $value) {
            $this->storage->set($key, $value);
        }

        return $this;
    }

    /**
     * @inheritDoc
     * @throws ContainerException
     */
    public function invoke(string $class, string $method, array $parameters = []): mixed
    {
        try {
            $instance = $this->get($class);

            return $this->resolver->resolveMethod($instance, $method, $parameters);
        } catch (\Exception $e) {
            throw new ContainerException("Can't resolve '$class::$method'. {$e->getMessage()}");
        }
    }
}