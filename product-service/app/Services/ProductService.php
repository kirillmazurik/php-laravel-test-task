<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ProductRepositoryInterface;
use App\Contracts\ProductServiceInterface;
use App\DTO\NewProductDTO;
use App\DTO\ProductDTO;
use App\DTO\UpdatedProductDTO;
use App\Exceptions\ProductException;
use Decimal\Decimal;
use PDOException;
use Psr\Log\LoggerInterface;

/**
 * @final
 */
class ProductService implements ProductServiceInterface
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
        private readonly LoggerInterface $logger,
    ) {

    }

    /**
     * @return ProductDTO[]
     */
    public function getList(): array
    {
        return $this
                ->productRepository
                ->getList();
    }

    public function create(
        string $name,
        string $description,
        Decimal $price,
    ): ProductDTO {
        $productDTO = new NewProductDTO($name, $description, $price);

        try {
            $product = $this
                ->productRepository
                ->save($productDTO);

            return $product->toDTO();
        } catch (PDOException $e) {
            $this
                ->logger
                ->error("ProductService:create: " . $e->getMessage(), [
                    'name' => $name,
                    'description' => $description,
                    'price' => $price,
            ]);

            throw new ProductException($e->getMessage());
        }
    }

    public function getById(int $id): ProductDTO
    {
        try {
            $product = $this
                ->productRepository
                ->getById($id);

            return $product->toDTO();
        } catch (PDOException $e) {
            $this
                ->logger
                ->error("ProductService:getById: " . $e->getMessage(), [
                    'id' => $id,
            ]);

            throw new ProductException($e->getMessage());
        }
    }

    public function deleteById(int $id): void
    {
        try {
            $this
                ->productRepository
                ->deleteById($id);
        } catch (PDOException $e) {
            $this
                ->logger
                ->error("ProductService:deleteById: " . $e->getMessage(), [
                    'id' => $id,
            ]);

            throw new ProductException($e->getMessage());
        }
    }

    public function updateById(
        int $id,
        string $name = "",
        string $description = "",
        ?Decimal $price = null,
    ): ProductDTO {
        try {
            $dto = new UpdatedProductDTO($id, $name, $description, $price);

            $product = $this
                ->productRepository
                ->updateById($id, $dto);

            return $product->toDTO();
        } catch (PDOException $e) {
            $this
                ->logger
                ->error("ProductService:updateById: " . $e->getMessage(), [
                    'id' => $id,
                    'name' => $name,
                    'description' => $description,
                    'price' => $price,
            ]);

            throw new ProductException($e->getMessage());
        }
    }

    /**
     * @return ProductDTO[]
     */
    public function search(string $name): array
    {
        return $this
                ->productRepository
                ->searchByName($name);
    }
}
