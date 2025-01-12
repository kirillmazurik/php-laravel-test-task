<?php

declare(strict_types=1);

namespace App\DTO;

use Decimal\Decimal;

final class UpdatedOrderDTO
{
    /**
     * @param NewProductDTO[] $productsDTO
     */
    public function __construct(
        public readonly ?int $addressId = null,
        public readonly ?Decimal $total = null,
        public readonly array $productsDTO = [],
    ) {

    }
}
