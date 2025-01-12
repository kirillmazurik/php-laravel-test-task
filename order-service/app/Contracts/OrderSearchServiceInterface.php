<?php

declare(strict_types=1);

namespace App\Contracts;

use App\DTO\OrderDTO;

interface OrderSearchServiceInterface
{
    /**
     * @return OrderDTO[]
     */
    public function search(string $name): array;
}
