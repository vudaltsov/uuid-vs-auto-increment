<?php

declare(strict_types=1);

namespace VUdaltsov\UuidVsAutoIncrement\Database;

interface Database
{
    /**
     * @template T of Table
     * @param class-string<T> $class
     * @return ?T
     */
    public function createTable(string $class): ?Table;
}
