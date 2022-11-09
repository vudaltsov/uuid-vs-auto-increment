<?php

declare(strict_types=1);

namespace VUdaltsov\UuidVsAutoIncrement;

return new class () implements WriterFactory {
    public function createWriter(string $benchmarkName, string $databaseName): Writer
    {
        return new Csv(__DIR__."/../report/{$benchmarkName}_{$databaseName}.csv");
    }
};
