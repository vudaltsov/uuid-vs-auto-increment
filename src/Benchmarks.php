<?php

declare(strict_types=1);

namespace VUdaltsov\UuidVsAutoIncrement;

final readonly class Benchmarks
{
    /**
     * @param array<string, callable(): Benchmark> $factories
     */
    public function __construct(
        private array $factories,
    ) {}

    /**
     * @param ?list<string> $names
     * @return \Generator<string, Benchmark>
     */
    public function get(?array $names = null): \Generator
    {
        foreach ($names ?? array_keys($this->factories) as $name) {
            if (!isset($this->factories[$name])) {
                throw new \RuntimeException(sprintf('Benchmark "%s" is not registered.', $name));
            }

            yield $name => ($this->factories[$name])();
        }
    }
}
