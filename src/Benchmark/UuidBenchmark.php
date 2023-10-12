<?php

declare(strict_types=1);

namespace VUdaltsov\UuidVsAutoIncrement\Benchmark;

use VUdaltsov\UuidVsAutoIncrement\Benchmark;
use VUdaltsov\UuidVsAutoIncrement\Database;
use VUdaltsov\UuidVsAutoIncrement\Writer;

final class UuidBenchmark implements Benchmark
{
    /**
     * @param callable(): string $uuidGenerator
     */
    public function __construct(
        private $uuidGenerator,
    ) {}

    public function run(Database $database, int $total, int $step, int $select, Writer $writer): void
    {
        $writer->write([
            'Rows',
            'Insert time, ms',
            'Select time, ms',
            'Index size, MiB',
        ]);

        $table = $database->createUuidTable();

        for ($rows = $step; $rows <= $total; $rows += $step) {
            $uuids = $this->generateUuids($step);
            /** @var non-empty-list<string> */
            $randomUuids = array_rand(array_flip($uuids), $select);

            $writer->write([
                sprintf('%dk', $rows / 1000),
                $table->measureInsertExecutionTime($uuids)->milliseconds(),
                $table->measureSelectExecutionTime($randomUuids)->milliseconds(),
                $table->measureIndexSize()->mebibytes(),
            ]);
        }
    }

    /**
     * @param positive-int $step
     * @return non-empty-list<string>
     */
    private function generateUuids(int $step): array
    {
        $uuids = [];

        for ($i = 0; $i < $step; ++$i) {
            $uuids[] = ($this->uuidGenerator)();
        }

        /** @var non-empty-list<string> */
        return $uuids;
    }
}
