<?php

declare(strict_types=1);

namespace App\Contracts;

use App\DTO\NewProductDTO;
use App\DTO\ProductDTO;
use App\DTO\UpdatedProductDTO;
use App\Models\Product;

interface ProductRepositoryInterface
{
    /**
     * @return ProductDTO[]
     */
    public function getList(): array;

    public function getById(int $id): Product;

    public function deleteById(int $id): void;

    public function save(NewProductDTO $productDTO): Product;

    public function updateById(int $id, UpdatedProductDTO $dto): Product;

    /**
     * @return ProductDTO[]
     */
    public function searchByName(string $name): array;
}
