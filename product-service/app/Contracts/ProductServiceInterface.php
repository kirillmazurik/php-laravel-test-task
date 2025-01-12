<?php

declare(strict_types=1);

namespace App\Contracts;

use App\DTO\ProductDTO;
use Decimal\Decimal;

interface ProductServiceInterface
{
    /**
     * @return ProductDTO[]
     */
    public function getList(): array;

    public function create(
        string $name,
        string $description,
        Decimal $price,
    ): ProductDTO;

    public function getById(int $id): ProductDTO;

    public function deleteById(int $id): void;

    public function updateById(
        int $id,
        string $name = "",
        string $description = "",
        ?Decimal $price = null,
    ): ProductDTO;

    /**
     * @return ProductDTO[]
     */
    public function search(string $name): array;
}
