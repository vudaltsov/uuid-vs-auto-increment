<?php

declare(strict_types=1);

namespace VUdaltsov\UuidVsAutoIncrement\Postgres;

use VUdaltsov\UuidVsAutoIncrement\Stopwatch\Memory;
use VUdaltsov\UuidVsAutoIncrement\Stopwatch\TimePeriod;
use VUdaltsov\UuidVsAutoIncrement\UuidTable;

final readonly class PostgresUuidTable implements UuidTable
{
    public function __construct(
        private PostgresDatabase $database,
    ) {
        $this->database->execute(
            <<<'SQL'
                drop table if exists uuid;
                create table uuid (
                    id uuid not null primary key
                )
                SQL,
        );
    }

    public function measureInsertExecutionTime(array $uuids): TimePeriod
    {
        $values = implode(',', array_map(
            static fn (string $uuid): string => "('{$uuid}')",
            $uuids,
        ));

        return $this->database->measureExecutionTime(
            <<<SQL
                insert into uuid (id)
                values {$values}
                SQL,
        );
    }

    public function measureSelectExecutionTime(array $uuids): TimePeriod
    {
        $inValue = implode(',', array_map(
            static fn (string $uuid): string => "'{$uuid}'",
            $uuids,
        ));

        return $this->database->measureExecutionTime(
            <<<SQL
                select id
                from uuid
                where id in ({$inValue})
                SQL,
        );
    }

    public function measureIndexSize(): Memory
    {
        return $this->database->measureIndexesSize('uuid');
    }
}
