<?php

declare(strict_types=1);

namespace VUdaltsov\UuidVsAutoIncrement;

use VUdaltsov\UuidVsAutoIncrement\Stopwatch\Stopwatch;

final class AutoIncrementBenchmark implements Benchmark
{
    /**
     * @param positive-int $iterations
     * @param positive-int $step
     */
    public function __construct(
        private readonly int $iterations = 100,
        private readonly int $step = 100_000,
    ) {
    }

    public function run(Database $database, Writer $writer): void
    {
        $writer->write([
            'Rows',
            'Insert time, ms',
            'Index size, MiB',
        ]);

        $database->prepareAutoIncrementTable();

        for ($i = 1; $i <= $this->iterations; ++$i) {
            $stopwatch = new Stopwatch();
            $database->insertAutoIncrements($this->step);
            $measurement = $stopwatch->measure();

            $writer->write([
                sprintf('%dk', $i * $this->step / 1000),
                $measurement->timeDiff()->milliseconds(),
                $database->calculateAutoIncrementIndexSize()->mebibytes(),
            ]);
        }
    }
}
