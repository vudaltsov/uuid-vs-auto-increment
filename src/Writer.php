<?php

declare(strict_types=1);

namespace VUdaltsov\UuidVsAutoIncrement;

interface Writer
{
    public function name(): string;

    /**
     * @param array<?scalar> $data
     */
    public function write(array $data): void;
}
