<?php

declare(strict_types=1);

namespace App\Contracts;

use App\DTO\NewOrderProductDTO;
use App\DTO\OrderDTO;

interface OrderServiceInterface
{
    /**
     * @return OrderDTO[]
     */
    public function getList(): array;

    /**
     * @param NewOrderProductDTO[] $productsDTO
     */
    public function create(
        int $adddressId,
        array $productsDTO,
    ): OrderDTO;

    public function getById(int $id): OrderDTO;

    public function deleteById(int $id): void;

    /**
     * @param NewOrderProductDTO[] $productsDTO
     */
    public function updateById(
        int $id,
        ?int $adddressId = null,
        array $productsDTO = [],
    ): OrderDTO;
}
