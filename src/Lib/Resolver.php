<?php

namespace Oktokod\DI\Lib;

use Oktokod\DI\ContainerInterface;

/**
 * @internal Used internally by oktokod/di library.
 */
class Resolver
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $class
     * @return mixed
     * @throws \ReflectionException
     */
    public function resolveClass(string $class): mixed
    {
        $reflection = new \ReflectionClass($class);

        $constructor = $reflection->getConstructor();

        if (!$constructor) {
            return $reflection->newInstance();
        }

        $parameters = $this->resolveParameters($constructor);

        return $reflection->newInstanceArgs($parameters);
    }

    /**
     * @param mixed $instance
     * @param string $method
     * @param array $parameters
     * @return mixed
     * @throws \ReflectionException
     */
    public function resolveMethod(mixed $instance, string $method, array $parameters = []): mixed
    {
        $reflection = new \ReflectionMethod($instance, $method);

        $parameters = $this->resolveParameters($reflection, $parameters);

        return $reflection->invokeArgs($instance, $parameters);
    }

    /**
     * @param \ReflectionMethod $reflection
     * @param array $parameters
     * @return array
     */
    private function resolveParameters(\ReflectionMethod $reflection, array $parameters = []): array
    {
        return array_map(function (\ReflectionParameter $parameter) use ($parameters) {
            return $this->resolveParameter($parameter, $parameters);
        }, $reflection->getParameters());
    }

    /**
     * @param \ReflectionParameter $parameter
     * @param array $parameters
     * @return mixed
     */
    private function resolveParameter(\ReflectionParameter $parameter, array $parameters = []): mixed
    {
        $name = $parameter->getName();

        if (array_key_exists($name, $parameters)) {
            return $parameters[$name];
        }

        $type = $parameter->getType();

        if ($type && !$type->isBuiltin()) {
            return $this->container->get($type->getName());
        }

        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }

        return null;
    }
}