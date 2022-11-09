<?php

declare(strict_types=1);

namespace VUdaltsov\UuidVsAutoIncrement\Stopwatch;

/**
 * @psalm-immutable
 */
final class IntervalMeasurement
{
    public function __construct(
        public readonly Measurement $before,
        public readonly Measurement $after,
    ) {
    }

    public function timeDiff(): TimePeriod
    {
        return TimePeriod::dateTimeDiff($this->after->time, $this->before->time);
    }

    public function memoryDiff(): Memory
    {
        return $this->after->memory->subtract($this->before->memory);
    }

    public function peakMemoryDiff(): Memory
    {
        return $this->after->peakMemory->subtract($this->before->peakMemory);
    }

    /**
     * @param 0|positive-int $precision
     */
    public function toString(int $precision = 3): string
    {
        return <<<TXT
            Time spent: {$this->timeDiff()->seconds($precision)} s
            Memory consumed: {$this->memoryDiff()->mebibytes($precision)} MiB
            Peak memory change: {$this->peakMemoryDiff()->mebibytes($precision)} MiB
            TXT;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
