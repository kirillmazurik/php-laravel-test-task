<?php

declare(strict_types=1);

namespace App\DTO;

final class AddressDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $address,
    ) {

    }

    /**
     * @return mixed[]
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'address' => $this->address,
        ];
    }
}
