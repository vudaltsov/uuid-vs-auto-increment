<?php

declare(strict_types=1);

namespace VUdaltsov\UuidVsAutoIncrement\Stopwatch;

/**
 * @psalm-immutable
 */
final class Memory
{
    private const KIBIBYTES_MULTIPLIER = 1024;
    private const MEBIBYTES_MULTIPLIER = self::KIBIBYTES_MULTIPLIER * 1024;
    private const GIGIBYTES_MULTIPLIER = self::MEBIBYTES_MULTIPLIER * 1024;

    public function __construct(
        public readonly int $bytes,
    ) {
    }

    /**
     * @psalm-pure
     */
    public static function fromKibibytes(int $kibibytes): self
    {
        return new self($kibibytes * self::KIBIBYTES_MULTIPLIER);
    }

    /**
     * @psalm-pure
     */
    public static function fromMemibytes(int $mebibytes): self
    {
        return new self($mebibytes * self::MEBIBYTES_MULTIPLIER);
    }

    /**
     * @psalm-pure
     */
    public static function fromGigibytes(int $gigibytes): self
    {
        return new self($gigibytes * self::GIGIBYTES_MULTIPLIER);
    }

    /**
     * @template TPrecision of 0|positive-int
     * @param TPrecision $precision
     * @param positive-int $roundingMode
     * @return (TPrecision is 0 ? int : float)
     */
    public function kibibytes(int $precision = 0, int $roundingMode = PHP_ROUND_HALF_UP): int|float
    {
        return $this->convert(self::KIBIBYTES_MULTIPLIER, $precision, $roundingMode);
    }

    /**
     * @template TPrecision of 0|positive-int
     * @param TPrecision $precision
     * @param positive-int $roundingMode
     * @return (TPrecision is 0 ? int : float)
     */
    public function mebibytes(int $precision = 0, int $roundingMode = PHP_ROUND_HALF_UP): int|float
    {
        return $this->convert(self::MEBIBYTES_MULTIPLIER, $precision, $roundingMode);
    }

    /**
     * @template TPrecision of 0|positive-int
     * @param TPrecision $precision
     * @param positive-int $roundingMode
     * @return (TPrecision is 0 ? int : float)
     */
    public function gigibytes(int $precision = 0, int $roundingMode = PHP_ROUND_HALF_UP): int|float
    {
        return $this->convert(self::GIGIBYTES_MULTIPLIER, $precision, $roundingMode);
    }

    public function add(self $memory): self
    {
        return new self($this->bytes + $memory->bytes);
    }

    public function subtract(self $memory): self
    {
        return new self($this->bytes - $memory->bytes);
    }

    /**
     * @template TPrecision of 0|positive-int
     * @param TPrecision $precision
     * @param positive-int $roundingMode
     * @return (TPrecision is 0 ? int : float)
     */
    private function convert(int $multiplier, int $precision = 0, int $roundingMode = PHP_ROUND_HALF_UP): int|float
    {
        $value = round($this->bytes / $multiplier, $precision, $roundingMode);

        if ($precision === 0) {
            return (int) $value;
        }

        return $value;
    }
}
