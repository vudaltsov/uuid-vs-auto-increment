<?php

declare(strict_types=1);

namespace VUdaltsov\UuidVsAutoIncrement;

return new Databases([
    'postgres' => static fn (): Postgres => new Postgres(),
]);
