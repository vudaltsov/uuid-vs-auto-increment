<?php

declare(strict_types=1);

namespace VUdaltsov\UuidVsAutoIncrement\AutoIncrementBenchmark;

use VUdaltsov\UuidVsAutoIncrement\Benchmark\Benchmark;
use VUdaltsov\UuidVsAutoIncrement\Benchmark\Writer;
use VUdaltsov\UuidVsAutoIncrement\Database\Database;

final class AutoIncrementBenchmark implements Benchmark
{
    public function run(Database $database, int $total, int $step, int $select, Writer $writer): void
    {
        $writer->write([
            'Rows',
            'Insert time, ms',
            'Select time, ms',
            'Index size, MiB',
        ]);

        $table = $database->createTable(AutoIncrementTable::class)
            ?? throw new \RuntimeException(sprintf(
                '"%s" does not support "%s".',
                $database::class,
                AutoIncrementTable::class,
            ));

        for ($rows = $step; $rows <= $total; $rows += $step) {
            $randomIds = $this->randomIds(
                min: $rows - $step,
                max: $rows,
                number: $select,
            );

            $writer->write([
                sprintf('%dk', $rows / 1000),
                $table->measureInsertExecutionTime($step)->milliseconds(),
                $table->measureSelectExecutionTime($randomIds)->milliseconds(),
                $table->measureIndexSize()->mebibytes(),
            ]);
        }
    }

    /**
     * @param positive-int $number
     * @return non-empty-list<int>
     */
    private function randomIds(int $min, int $max, int $number): array
    {
        $ids = [];

        for ($i = 0; $i < $number; ++$i) {
            $ids[] = random_int($min, $max);
        }

        /** @var non-empty-list<int> */
        return $ids;
    }
}
