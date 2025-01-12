<?php

declare(strict_types=1);

namespace App\DTO;

use Decimal\Decimal;

final class NewProductDTO
{
    public Decimal $total;

    public function __construct(
        public readonly int $productId,
        public readonly int $count,
        public readonly Decimal $sellPrice,
    ) {
        $this->total = $this->sellPrice->mul($this->count);
    }
}
