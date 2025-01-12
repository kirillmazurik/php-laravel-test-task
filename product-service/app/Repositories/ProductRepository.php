<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\ProductRepositoryInterface;
use App\DTO\NewProductDTO;
use App\DTO\ProductDTO;
use App\DTO\UpdatedProductDTO;
use App\Models\Product;
use PDOException;

/**
 * @final
 */
class ProductRepository implements ProductRepositoryInterface
{
    /**
     * @return ProductDTO[]
     */
    public function getList(): array
    {
        $products = Product::query()->get();

        $result = [];

        /** @var Product $product */
        foreach ($products as $product) {
            $result[] = $product->toDTO();
        }

        return $result;
    }

    public function save(NewProductDTO $productDTO): Product
    {
        $product = new Product();
        $product->name = $productDTO->name;
        $product->description = $productDTO->description;
        $product->price = $productDTO->price;
        $product->save();

        return $product;
    }

    public function getById(int $id): Product
    {
        /** @var Product|null $product */
        $product = Product::query()->find($id);

        if ($product === null) {
            throw new PDOException("Cannot find a product");
        }

        return $product;
    }

    public function deleteById(int $id): void
    {
        /** @var Product|null $product */
        $product = Product::query()->find($id);

        if ($product === null) {
            throw new PDOException("Cannot delete a product");
        }

        $product->delete();
    }

    public function updateById(int $id, UpdatedProductDTO $dto): Product
    {
        /** @var Product|null $product */
        $product = Product::query()->find($id);

        if ($product === null) {
            throw new PDOException("Cannot update a product");
        }

        if ($dto->name !== "") {
            $product->name = $dto->name;
        }

        if ($dto->description !== "") {
            $product->description = $dto->description;
        }

        if ($dto->price !== null) {
            $product->price = $dto->price;
        }

        $product->save();

        return $product;
    }

    /**
     * @return ProductDTO[]
     */
    public function searchByName(string $name): array
    {
        $products = Product::query()
            ->where("name", "LIKE", "%" . $name . "%")
            ->get();

        $result = [];

        /** @var Product $product * */
        foreach ($products as $product) {
            $result[] = $product->toDTO();
        }

        return $result;
    }
}
