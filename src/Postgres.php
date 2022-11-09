<?php

declare(strict_types=1);

namespace VUdaltsov\UuidVsAutoIncrement;

use PgSql\Connection;
use PgSql\Result;
use VUdaltsov\UuidVsAutoIncrement\Stopwatch\Memory;

final class Postgres implements Database
{
    private readonly Connection $connection;

    /**
     * @param non-empty-string $dbName
     * @param non-empty-string $user
     * @param non-empty-string $password
     * @param non-empty-string $host
     * @param positive-int     $port
     */
    public function __construct(
        string $host = 'localhost',
        int $port = 5432,
        string $dbName = 'root',
        string $user = 'root',
        string $password = 'root',
    ) {
        $this->connection = pg_connect("host={$host} port={$port} dbname={$dbName} user={$user} password={$password}");
    }

    public function prepareUuidTable(): void
    {
        $this->execute(
            <<<'SQL'
                drop table if exists uuid_benchmark;
                create table uuid_benchmark (
                    id uuid not null primary key
                );
                SQL,
        );
    }

    public function insertUuids(array $uuids): void
    {
        $values = implode(',', array_map(
            static fn (string $uuid): string => sprintf("('%s')", $uuid),
            $uuids,
        ));

        $this->execute(
            <<<SQL
                insert into uuid_benchmark (id)
                values {$values}
                SQL,
        );
    }

    public function calculateUuidIndexSize(): Memory
    {
        /** @var array{size: numeric-string} */
        $row = $this->query(
            <<<'SQL'
                select pg_indexes_size('uuid_benchmark'::regclass) as size
                SQL,
        ) ?? throw new \LogicException();

        return new Memory((int) $row['size']);
    }

    public function prepareAutoIncrementTable(): void
    {
        $this->execute(
            <<<'SQL'
                drop table if exists auto_increment_benchmark;
                create table auto_increment_benchmark (
                    id integer generated always as identity not null primary key
                );
                SQL,
        );
    }

    public function insertAutoIncrements(int $number): void
    {
        $values = implode(',', array_fill(0, $number, '(default)'));

        $this->execute(
            <<<SQL
                insert into auto_increment_benchmark (id)
                values {$values}
                SQL,
        );
    }

    public function calculateAutoIncrementIndexSize(): Memory
    {
        /** @var array{size: numeric-string} */
        $row = $this->query(
            <<<'SQL'
                select pg_indexes_size('auto_increment_benchmark'::regclass) as size
                SQL,
        ) ?? throw new \LogicException();

        return new Memory((int) $row['size']);
    }

    private function execute(string $query): Result
    {
        return pg_query($this->connection, $query);
    }

    private function query(string $query): ?array
    {
        $row = pg_fetch_array($this->execute($query));

        if ($row === false) {
            return null;
        }

        return $row;
    }
}
