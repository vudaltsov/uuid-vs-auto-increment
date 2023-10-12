<?php

declare(strict_types=1);

namespace VUdaltsov\UuidVsAutoIncrement\Stopwatch;

/**
 * @psalm-immutable
 */
final readonly class Memory
{
    private const KIBIBYTES_MULTIPLIER = 1024;
    private const MEBIBYTES_MULTIPLIER = self::KIBIBYTES_MULTIPLIER * 1024;

    public function __construct(
        public int $bytes,
    ) {}

    /**
     * @template TPrecision of 0|positive-int
     * @param TPrecision $precision
     * @param positive-int $roundingMode
     * @return (TPrecision is 0 ? int : float)
     */
    public function mebibytes(int $precision = 0, int $roundingMode = PHP_ROUND_HALF_UP): float|int
    {
        return $this->convert(self::MEBIBYTES_MULTIPLIER, $precision, $roundingMode);
    }

    /**
     * @template TPrecision of 0|positive-int
     * @param TPrecision $precision
     * @param positive-int $roundingMode
     * @return (TPrecision is 0 ? int : float)
     */
    private function convert(int $multiplier, int $precision = 0, int $roundingMode = PHP_ROUND_HALF_UP): float|int
    {
        $value = round($this->bytes / $multiplier, $precision, $roundingMode);

        if ($precision === 0) {
            return (int) $value;
        }

        return $value;
    }
}
