<?php

declare(strict_types=1);

namespace App\DTO;

use Decimal\Decimal;

final class UpdatedProductDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name = "",
        public readonly string $description = "",
        public readonly ?Decimal $price = null,
    ) {

    }

    /**
     * @return mixed[]
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
        ];
    }
}
