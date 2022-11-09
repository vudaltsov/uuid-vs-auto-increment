<?php

declare(strict_types=1);

namespace VUdaltsov\UuidVsAutoIncrement\Stopwatch;

/**
 * @psalm-immutable
 */
final class TimePeriod
{
    private const MILLISECONDS_MULTIPLIER = 1000;
    private const SECONDS_MULTIPLIER = self::MILLISECONDS_MULTIPLIER * 1000;
    private const MINUTES_MULTIPLIER = self::SECONDS_MULTIPLIER * 60;

    public function __construct(
        public readonly int $microseconds,
    ) {
    }

    /**
     * @psalm-pure
     */
    public static function dateTimeDiff(\DateTimeImmutable $a, \DateTimeImmutable $b): self
    {
        return new self((int) $a->format('Uu') - (int) $b->format('Uu'));
    }

    /**
     * @psalm-pure
     */
    public static function fromMilliseconds(int $milliseconds): self
    {
        return new self($milliseconds * self::MILLISECONDS_MULTIPLIER);
    }

    /**
     * @psalm-pure
     */
    public static function fromSeconds(int $seconds): self
    {
        return new self($seconds * self::SECONDS_MULTIPLIER);
    }

    /**
     * @psalm-pure
     */
    public static function fromMinutes(int $minutes): self
    {
        return new self($minutes * self::MINUTES_MULTIPLIER);
    }

    /**
     * @template TPrecision of 0|positive-int
     * @param TPrecision $precision
     * @param positive-int $roundingMode
     * @return (TPrecision is 0 ? int : float)
     */
    public function milliseconds(int $precision = 0, int $roundingMode = PHP_ROUND_HALF_UP): int|float
    {
        return $this->convert(self::MILLISECONDS_MULTIPLIER, $precision, $roundingMode);
    }

    /**
     * @template TPrecision of 0|positive-int
     * @param TPrecision $precision
     * @param positive-int $roundingMode
     * @return (TPrecision is 0 ? int : float)
     */
    public function seconds(int $precision = 0, int $roundingMode = PHP_ROUND_HALF_UP): int|float
    {
        return $this->convert(self::SECONDS_MULTIPLIER, $precision, $roundingMode);
    }

    /**
     * @template TPrecision of 0|positive-int
     * @param TPrecision $precision
     * @param positive-int $roundingMode
     * @return (TPrecision is 0 ? int : float)
     */
    public function minutes(int $precision = 0, int $roundingMode = PHP_ROUND_HALF_UP): int|float
    {
        return $this->convert(self::MINUTES_MULTIPLIER, $precision, $roundingMode);
    }

    public function add(self $period): self
    {
        return new self($this->microseconds + $period->microseconds);
    }

    public function subtract(self $period): self
    {
        return new self($this->microseconds - $period->microseconds);
    }

    /**
     * @template TPrecision of 0|positive-int
     * @param TPrecision $precision
     * @param positive-int $roundingMode
     * @return (TPrecision is 0 ? int : float)
     */
    private function convert(int $multiplier, int $precision = 0, int $roundingMode = PHP_ROUND_HALF_UP): int|float
    {
        $value = round($this->microseconds / $multiplier, $precision, $roundingMode);

        if ($precision === 0) {
            return (int) $value;
        }

        return $value;
    }
}
