<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\AddressRepositoryInterface;
use App\Contracts\AddressServiceInterface;
use App\DTO\AddressDTO;
use App\Exceptions\AddressException;
use PDOException;
use Psr\Log\LoggerInterface;

/**
 * @final
 */
class AddressService implements AddressServiceInterface
{
    public function __construct(
        private readonly AddressRepositoryInterface $addressRepository,
        private readonly LoggerInterface $logger,
    ) {

    }

    /**
     * @return AddressDTO[]
     */
    public function getList(): array
    {
        return $this
                ->addressRepository
                ->getList();
    }

    public function getById(int $id): AddressDTO
    {
        try {
            $address = $this
                ->addressRepository
                ->getById($id);

            return $address->toDTO();
        } catch (PDOException $e) {
            $this
                ->logger
                ->error("AddressService:getById: " . $e->getMessage(), [
                    'id' => $id,
            ]);

            throw new AddressException($e->getMessage());
        }
    }
}
