<?php

declare(strict_types=1);

namespace VUdaltsov\UuidVsAutoIncrement\Benchmark;

use VUdaltsov\UuidVsAutoIncrement\Database\Database;

interface Benchmark
{
    /**
     * @param positive-int $total
     * @param positive-int $step
     * @param positive-int $select
     */
    public function run(Database $database, int $total, int $step, int $select, Writer $writer): void;
}
