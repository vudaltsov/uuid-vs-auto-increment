<?php

declare(strict_types=1);

namespace VUdaltsov\UuidVsAutoIncrement;

use VUdaltsov\UuidVsAutoIncrement\Stopwatch\Memory;

interface Database
{
    public function prepareUuidTable(): void;

    /**
     * @param non-empty-list<string> $uuids
     */
    public function insertUuids(array $uuids): void;

    public function calculateUuidIndexSize(): Memory;

    public function prepareAutoIncrementTable(): void;

    /**
     * @param positive-int $number
     */
    public function insertAutoIncrements(int $number): void;

    public function calculateAutoIncrementIndexSize(): Memory;
}
