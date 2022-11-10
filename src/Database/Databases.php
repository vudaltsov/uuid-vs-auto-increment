<?php

declare(strict_types=1);

namespace VUdaltsov\UuidVsAutoIncrement\Database;

final class Databases
{
    /**
     * @param array<string, callable(): Database> $factories
     */
    public function __construct(
        private readonly array $factories,
    ) {
    }

    /**
     * @param ?list<string> $names
     * @return \Generator<string, Database>
     */
    public function get(?array $names = null): \Generator
    {
        foreach ($names ?? array_keys($this->factories) as $name) {
            if (!isset($this->factories[$name])) {
                throw new \RuntimeException(sprintf('Database "%s" is not registered.', $name));
            }

            yield $name => ($this->factories[$name])();
        }
    }
}
