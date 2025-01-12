<?php

declare(strict_types=1);

namespace App\Contracts;

use App\DTO\AddressDTO;

interface AddressServiceInterface
{
    /**
     * @return AddressDTO[]
     */
    public function getList(): array;

    public function getById(int $id): AddressDTO;
}
