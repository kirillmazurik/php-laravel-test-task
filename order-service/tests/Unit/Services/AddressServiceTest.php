<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\DTO\AddressDTO;
use App\Models\Address;
use App\Repositories\AddressRepository;
use App\Services\AddressService;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class AddressServiceTest extends TestCase
{
    public function testGetList(): void
    {
        $addressRepositoryMock = $this
            ->getMockBuilder(AddressRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $dto = new AddressDTO(1, "address-1");

        $addressRepositoryMock
            ->method('getList')
            ->willReturn([
                $dto,
        ]);

        $service = new AddressService(
            $addressRepositoryMock,
            new NullLogger(),
        );

        /** @var AddressDTO[] $result * */
        $result = $service->getList();
        self::assertIsArray($result);
        self::assertCount(1, $result);
        self::assertSame($result[0]->id, 1);
        self::assertSame($result[0]->address, "address-1");
    }

    public function testGetById(): void
    {
        $addressRepositoryMock = $this
            ->getMockBuilder(AddressRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $adderss = new Address();
        $adderss->setAttribute('id', 1);
        $adderss->address = 'address-1';

        $addressRepositoryMock
            ->method('getById')
            ->willReturn($adderss);

        $service = new AddressService(
            $addressRepositoryMock,
            new NullLogger(),
        );


        $result = $service->getById(1);
        self::assertInstanceOf(AddressDTO::class, $result);
        self::assertSame($result->id, 1);
        self::assertSame($result->id, 1);
        self::assertSame($result->address, 'address-1');
    }
}
