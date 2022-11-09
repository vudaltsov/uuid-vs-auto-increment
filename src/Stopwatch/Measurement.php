<?php

declare(strict_types=1);

namespace VUdaltsov\UuidVsAutoIncrement\Stopwatch;

/**
 * @psalm-immutable
 */
final class Measurement
{
    public function __construct(
        public readonly \DateTimeImmutable $time,
        public readonly Memory $memory,
        public readonly Memory $peakMemory,
    ) {
    }

    public static function make(): self
    {
        return new self(
            time: new \DateTimeImmutable(),
            memory: new Memory(memory_get_usage()),
            peakMemory: new Memory(memory_get_peak_usage()),
        );
    }

    /**
     * @param 0|positive-int $precision
     */
    public function toString(int $precision = 3): string
    {
        return <<<TXT
            Date: {$this->time->format('d.m.Y H:i:s.u P')}
            Memory usage: {$this->memory->mebibytes($precision)} MiB
            Peak memory usage: {$this->peakMemory->mebibytes($precision)} MiB
            TXT;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
