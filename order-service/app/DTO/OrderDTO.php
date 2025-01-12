<?php

declare(strict_types=1);

namespace App\DTO;

use Decimal\Decimal;

final class OrderDTO
{
    /**
     * @param int[] $orderProducts
     */
    public function __construct(
        public readonly int $id,
        public readonly int $addressId,
        public readonly string $address,
        public readonly array $orderProducts,
        public readonly Decimal $total,
    ) {

    }

    /**
     * @return mixed[]
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'address_id' => $this->addressId,
            'address' => $this->address,
            'orderProducts' => $this->orderProducts,
            'total' => $this->total,
        ];
    }
}
