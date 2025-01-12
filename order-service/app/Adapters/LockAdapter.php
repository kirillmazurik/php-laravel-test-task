<?php

declare(strict_types=1);

namespace App\Adapters;

use App\Contracts\LockAdapterInterface;
use Illuminate\Cache\CacheManager;

final class LockAdapter implements LockAdapterInterface
{
    private const DEFAULT_SECONDS = 5;

    public function __construct(
        private readonly CacheManager $cacheManager,
    ) {

    }

    public function lock(string $name, int $seconds): bool
    {
        if ($seconds < 0) {
            $seconds = self::DEFAULT_SECONDS;
        }

        $lock = $this
            ->cacheManager
            ->lock($name, $seconds);

        return (bool)$lock->get();
    }

    public function unlock(string $name): void
    {
        $this
            ->cacheManager
            ->lock($name)
            ->forceRelease();
    }
}
