<?php

declare(strict_types=1);

namespace VUdaltsov\UuidVsAutoIncrement\AutoIncrementBenchmark;

use VUdaltsov\UuidVsAutoIncrement\Database\Table;
use VUdaltsov\UuidVsAutoIncrement\Stopwatch\Memory;
use VUdaltsov\UuidVsAutoIncrement\Stopwatch\TimePeriod;

interface AutoIncrementTable extends Table
{
    public function measureInsertExecutionTime(int $rowsNumber): TimePeriod;

    /**
     * @param non-empty-list<int> $ids
     */
    public function measureSelectExecutionTime(array $ids): TimePeriod;

    public function measureIndexSize(): Memory;
}
