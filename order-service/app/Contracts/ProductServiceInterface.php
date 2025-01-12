<?php

declare(strict_types=1);

namespace App\Contracts;

use Decimal\Decimal;

interface ProductServiceInterface
{
    public function getPriceById(int $id): Decimal;

    /**
     * @return int[]
     */
    public function getProductsByName(string $name): array;
}
