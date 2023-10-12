<?php

declare(strict_types=1);

namespace VUdaltsov\UuidVsAutoIncrement\Writer;

use VUdaltsov\UuidVsAutoIncrement\Writer;

final class Csv implements Writer
{
    /**
     * @var resource
     */
    private $handle;

    public function __construct(
        private readonly string $file,
        private readonly string $separator = ',',
        private readonly string $enclosure = '"',
        private readonly string $escape = '\\',
    ) {
        $this->handle = fopen($file, 'w');
    }

    public function __destruct()
    {
        fclose($this->handle);
    }

    public function name(): string
    {
        return $this->file;
    }

    public function write(array $data): void
    {
        fputcsv(
            stream: $this->handle,
            fields: $data,
            separator: $this->separator,
            enclosure: $this->enclosure,
            escape: $this->escape,
        );
    }
}
