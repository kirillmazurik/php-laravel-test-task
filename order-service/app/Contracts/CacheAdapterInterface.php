<?php

declare(strict_types=1);

namespace App\Contracts;

interface CacheAdapterInterface
{
    public function set(string $name, mixed $data, int $seconds): bool;

    public function get(string $name): mixed;
}
