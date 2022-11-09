<?php

declare(strict_types=1);

namespace VUdaltsov\UuidVsAutoIncrement;

/**
 * @template T of object
 * @param class-string<T> $class
 * @return T
 */
function loadConfig(string $name, string $class): mixed
{
    try {
        /** @psalm-suppress UnresolvableInclude */
        $config = include_once __DIR__."/../config/{$name}.php";
    } catch (\Throwable) {
        /** @psalm-suppress UnresolvableInclude, MixedAssignment */
        $config = require_once __DIR__."/../config/{$name}.dist.php";
    }

    \assert($config instanceof $class);

    return $config;
}

function loadBenchmarks(): Benchmarks
{
    return loadConfig('benchmarks', Benchmarks::class);
}

function loadDatabases(): Databases
{
    return loadConfig('databases', Databases::class);
}

function loadWriterFactory(): WriterFactory
{
    return loadConfig('writer', WriterFactory::class);
}
