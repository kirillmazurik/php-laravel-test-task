<?php

declare(strict_types=1);

namespace App\DTO;

use Decimal\Decimal;

final class NewOrderDTO
{
    /**
     * @param NewProductDTO[] $productsDTO
     */
    public function __construct(
        public readonly int $addressId,
        public readonly Decimal $total,
        public readonly array $productsDTO,
    ) {

    }
}
