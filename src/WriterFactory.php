<?php

declare(strict_types=1);

namespace VUdaltsov\UuidVsAutoIncrement;

interface WriterFactory
{
    public function createWriter(string $benchmarkName, string $databaseName): Writer;
}
