<?php

declare(strict_types=1);

namespace VUdaltsov\UuidVsAutoIncrement\Stopwatch;

final class Stopwatch
{
    /**
     * @var callable(): Measurement
     */
    private static $measurementFactory = [Measurement::class, 'make'];

    /**
     * @var array<string, self>
     */
    private static array $stopwatches = [];

    /**
     * @psalm-readonly-allow-private-mutation
     */
    public Measurement $before;

    public function __construct()
    {
        $this->before = (self::$measurementFactory)();
    }

    /**
     * @param callable(): Measurement $measurementFactory
     */
    public static function setMeasurementFactory(callable $measurementFactory): void
    {
        self::$measurementFactory = $measurementFactory;
    }

    public static function get(string $name = 'default'): self
    {
        return self::$stopwatches[$name] ??= new self();
    }

    public static function remove(string $name = 'default'): void
    {
        unset(self::$stopwatches[$name]);
    }

    public function measure(): IntervalMeasurement
    {
        return new IntervalMeasurement($this->before, (self::$measurementFactory)());
    }

    public function measureAndSetAsBefore(): IntervalMeasurement
    {
        $after = (self::$measurementFactory)();
        $interval = new IntervalMeasurement($this->before, $after);
        $this->before = $after;

        return $interval;
    }
}
