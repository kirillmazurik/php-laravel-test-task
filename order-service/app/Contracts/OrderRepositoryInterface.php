<?php

declare(strict_types=1);

namespace App\Contracts;

use App\DTO\NewOrderDTO;
use App\DTO\OrderDTO;
use App\DTO\UpdatedOrderDTO;
use App\Models\Order;

interface OrderRepositoryInterface
{
    /**
     * @return OrderDTO[]
     */
    public function getList(): array;

    /**
     * @param int[] $ids
     * @return OrderDTO[]
     */
    public function getListByIds(array $ids): array;

    public function getById(int $id): Order;

    public function deleteById(int $id): void;

    public function save(NewOrderDTO $OrderDTO): Order;

    public function updateById(int $id, UpdatedOrderDTO $dto): Order;

    /**
     * @return OrderDTO[]
     */
    public function searchByName(string $name): array;
}
