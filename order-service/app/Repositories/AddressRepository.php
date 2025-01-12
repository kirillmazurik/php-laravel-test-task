<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\AddressRepositoryInterface;
use App\DTO\AddressDTO;
use App\Models\Address;
use PDOException;

/**
 * @final
 */
class AddressRepository implements AddressRepositoryInterface
{
    /**
     * @return AddressDTO[]
     */
    public function getList(): array
    {
        $addresses = Address::query()->get();

        $result = [];

        /** @var Address $address */
        foreach ($addresses as $address) {
            $result[] = $address->toDTO();
        }

        return $result;
    }

    public function getById(int $id): Address
    {
        /** @var Address|null $address */
        $address = Address::query()->find($id);

        if ($address === null) {
            throw new PDOException("Cannot find an address");
        }

        return $address;
    }
}
