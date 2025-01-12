<?php

declare(strict_types=1);

namespace App\Adapters;

use App\Contracts\CacheAdapterInterface;
use Illuminate\Cache\CacheManager;

/**
 * @final
 */
class CacheAdapter implements CacheAdapterInterface
{
    private const DEFAULT_SECONDS = 60;

    public function __construct(
        private readonly CacheManager $cacheManager,
    ) {

    }

    public function set(string $name, mixed $data, int $seconds): bool
    {
        if ($seconds < 0) {
            $seconds = self::DEFAULT_SECONDS;
        }

        return $lock = $this
            ->cacheManager
            ->set($name, $data, $seconds);
    }

    public function get(string $name): mixed
    {
        return $this
            ->cacheManager
            ->get($name);
    }
}
