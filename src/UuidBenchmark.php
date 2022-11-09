<?php

declare(strict_types=1);

namespace VUdaltsov\UuidVsAutoIncrement;

use VUdaltsov\UuidVsAutoIncrement\Stopwatch\Stopwatch;

final class UuidBenchmark implements Benchmark
{
    /**
     * @param callable(): string $uuidGenerator
     * @param positive-int $iterations
     * @param positive-int $step
     */
    public function __construct(
        private $uuidGenerator,
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

        $database->prepareUuidTable();

        for ($i = 1; $i <= $this->iterations; ++$i) {
            $uuids = $this->generateUuids();

            $stopwatch = new Stopwatch();
            $database->insertUuids($uuids);
            $measurement = $stopwatch->measure();

            $writer->write([
                sprintf('%dk', $i * $this->step / 1000),
                $measurement->timeDiff()->milliseconds(),
                $database->calculateUuidIndexSize()->mebibytes(),
            ]);
        }
    }

    /**
     * @return non-empty-list<string>
     */
    private function generateUuids(): array
    {
        $uuids = [];

        for ($i = 0; $i < $this->step; ++$i) {
            $uuids[] = ($this->uuidGenerator)();
        }

        /** @var non-empty-list<string> */
        return $uuids;
    }
}
