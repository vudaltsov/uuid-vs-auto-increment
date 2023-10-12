<?php

declare(strict_types=1);

namespace VUdaltsov\UuidVsAutoIncrement\Stopwatch;

/**
 * @psalm-immutable
 */
final readonly class TimePeriod
{
    private const MILLISECONDS_MULTIPLIER = 1000;
    private const SECONDS_MULTIPLIER = self::MILLISECONDS_MULTIPLIER * 1000;
    private const MINUTES_MULTIPLIER = self::SECONDS_MULTIPLIER * 60;

    public function __construct(
        public int $microseconds,
    ) {}

    /**
     * @psalm-pure
     */
    public static function dateTimeDiff(\DateTimeImmutable $a, \DateTimeImmutable $b): self
    {
        return new self((int) $a->format('Uu') - (int) $b->format('Uu'));
    }

    /**
     * @template TPrecision of 0|positive-int
     * @param TPrecision $precision
     * @param positive-int $roundingMode
     * @return (TPrecision is 0 ? int : float)
     */
    public function milliseconds(int $precision = 0, int $roundingMode = PHP_ROUND_HALF_UP): float|int
    {
        return $this->convert(self::MILLISECONDS_MULTIPLIER, $precision, $roundingMode);
    }

    /**
     * @template TPrecision of 0|positive-int
     * @param TPrecision $precision
     * @param positive-int $roundingMode
     * @return (TPrecision is 0 ? int : float)
     */
    public function seconds(int $precision = 0, int $roundingMode = PHP_ROUND_HALF_UP): float|int
    {
        return $this->convert(self::SECONDS_MULTIPLIER, $precision, $roundingMode);
    }

    /**
     * @template TPrecision of 0|positive-int
     * @param TPrecision $precision
     * @param positive-int $roundingMode
     * @return (TPrecision is 0 ? int : float)
     */
    private function convert(int $multiplier, int $precision = 0, int $roundingMode = PHP_ROUND_HALF_UP): float|int
    {
        $value = round($this->microseconds / $multiplier, $precision, $roundingMode);

        if ($precision === 0) {
            return (int) $value;
        }

        return $value;
    }
}
