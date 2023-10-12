<?php

declare(strict_types=1);

namespace VUdaltsov\UuidVsAutoIncrement;

interface Database
{
    public function createAutoIncrementTable(): AutoIncrementTable;

    public function createUuidTable(): UuidTable;
}
