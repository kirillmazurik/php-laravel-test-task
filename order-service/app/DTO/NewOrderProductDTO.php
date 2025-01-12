<?php

declare(strict_types=1);

namespace App\DTO;

final class NewOrderProductDTO
{
    public function __construct(
        public readonly int $productId,
        public readonly int $count,
    ) {
    }
}
