<?php

declare(strict_types=1);

namespace VUdaltsov\UuidVsAutoIncrement\Benchmark;

use VUdaltsov\UuidVsAutoIncrement\Benchmark;
use VUdaltsov\UuidVsAutoIncrement\Database;
use VUdaltsov\UuidVsAutoIncrement\Writer;

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

        $table = $database->createAutoIncrementTable();

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
