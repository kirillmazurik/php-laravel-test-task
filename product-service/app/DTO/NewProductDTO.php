<?php

declare(strict_types=1);

namespace App\DTO;

use Decimal\Decimal;

final class NewProductDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public readonly Decimal $price,
    ) {

    }
}
