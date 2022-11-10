<?php

declare(strict_types=1);

namespace VUdaltsov\UuidVsAutoIncrement\UuidBenchmark;

use VUdaltsov\UuidVsAutoIncrement\Database\Table;
use VUdaltsov\UuidVsAutoIncrement\Stopwatch\Memory;
use VUdaltsov\UuidVsAutoIncrement\Stopwatch\TimePeriod;

interface UuidTable extends Table
{
    /**
     * @param non-empty-list<string> $uuids
     */
    public function measureInsertExecutionTime(array $uuids): TimePeriod;

    /**
     * @param non-empty-list<string> $uuids
     */
    public function measureSelectExecutionTime(array $uuids): TimePeriod;

    public function measureIndexSize(): Memory;
}
