<?php

declare(strict_types=1);

namespace VUdaltsov\UuidVsAutoIncrement;

interface Benchmark
{
    public function run(Database $database, Writer $writer): void;
}
