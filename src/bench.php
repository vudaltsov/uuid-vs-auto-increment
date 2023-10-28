#!/usr/bin/env php
<?php

declare(strict_types=1);

namespace VUdaltsov\UuidVsAutoIncrement;

use Ramsey\Uuid\Type\Hexadecimal;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Application;
use VUdaltsov\UuidVsAutoIncrement\Benchmark\AutoIncrementBenchmark;
use VUdaltsov\UuidVsAutoIncrement\Benchmark\UuidBenchmark;
use VUdaltsov\UuidVsAutoIncrement\Postgres\PostgresDatabase;
use VUdaltsov\UuidVsAutoIncrement\Writer\Csv;
use function Ramsey\Uuid\v1;
use function Ramsey\Uuid\v4;
use function Ramsey\Uuid\v5;
use function Ramsey\Uuid\v7;

error_reporting(E_ALL);
set_error_handler(static fn ($severity, $message, $file, $line) => throw new \ErrorException(message: $message, severity: $severity, filename: $file, line: $line));

require_once __DIR__ . '/../vendor/autoload.php';

$node = new Hexadecimal('0');
$application = new Application();
$application->add(new BenchmarkCommand(
    benchmarks: new Benchmarks([
        'auto_increment' => static fn (): AutoIncrementBenchmark => new AutoIncrementBenchmark(),
        'uuid_v1' => static fn (): UuidBenchmark => new UuidBenchmark(
            static fn (): string => v1($node),
        ),
        'uuid_v7' => static fn (): UuidBenchmark => new UuidBenchmark(
            static fn (): string => v7(),
        ),
        'uuid_v4' => static fn (): UuidBenchmark => new UuidBenchmark(
            static fn (): string => v4(),
        ),
        'uuid_v5' => static fn (): UuidBenchmark => new UuidBenchmark(
            static fn (): string => v5(Uuid::NIL, random_bytes(16)),
        ),
    ]),
    databases: new Databases([
        'postgres' => static fn (): PostgresDatabase => new PostgresDatabase(/*host: 'postgres'*/),
    ]),
    writerFactory: static fn (string $name) => new Csv(__DIR__ . "/../data/{$name}.csv"),
));
$application->setDefaultCommand('bench', true);

/** @psalm-suppress UncaughtThrowInGlobalScope */
$application->run();
