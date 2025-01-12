<?php

declare(strict_types=1);

namespace App\Contracts;

interface LockAdapterInterface
{
    public function lock(string $name, int $seconds): bool;

    public function unlock(string $name): void;
}
