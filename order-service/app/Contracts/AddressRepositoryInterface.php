<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Address;
use App\DTO\AddressDTO;

interface AddressRepositoryInterface
{
    /**
     * @return AddressDTO[]
     */
    public function getList(): array;

    public function getById(int $id): Address;
}
