<?php

declare(strict_types=1);

namespace VUdaltsov\UuidVsAutoIncrement;

use Ramsey\Uuid\Type\Hexadecimal;
use Ramsey\Uuid\Uuid;
use function Ramsey\Uuid\v4;
use function Ramsey\Uuid\v5;
use function Ramsey\Uuid\v6;
use function Ramsey\Uuid\v7;
use function Ramsey\Uuid\v8;

$iterations = 1;
$node = new Hexadecimal('0');

return new Benchmarks([
    'auto_increment' => static fn (): AutoIncrementBenchmark => new AutoIncrementBenchmark(
        iterations: $iterations,
    ),
    'uuid_v4' => static fn (): UuidBenchmark => new UuidBenchmark(
        uuidGenerator: v4(...),
        iterations: $iterations,
    ),
    'uuid_v5' => static fn (): UuidBenchmark => new UuidBenchmark(
        uuidGenerator: static fn (): string => v5(Uuid::NIL, random_bytes(16)),
        iterations: $iterations,
    ),
    'uuid_v6' => static fn (): UuidBenchmark => new UuidBenchmark(
        uuidGenerator: static fn (): string => v6($node),
        iterations: $iterations,
    ),
    'uuid_v7' => static fn (): UuidBenchmark => new UuidBenchmark(
        uuidGenerator: static fn (): string => v7(),
        iterations: $iterations,
    ),
    'uuid_v8' => static fn (): UuidBenchmark => new UuidBenchmark(
        uuidGenerator: static fn (): string => v8(random_bytes(16)),
        iterations: $iterations,
    ),
]);
