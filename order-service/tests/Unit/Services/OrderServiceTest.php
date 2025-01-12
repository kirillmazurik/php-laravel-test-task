<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\DTO\AddressDTO;
use App\DTO\NewOrderProductDTO;
use App\DTO\OrderDTO;
use App\Models\Order;
use App\Repositories\OrderRepository;
use App\Services\AddressService;
use App\Services\OrderService;
use App\Services\RemoteProductService;
use Decimal\Decimal;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class OrderServiceTest extends TestCase
{
    public function testGetList(): void
    {
        $orderRepositoryMock = $this
            ->getMockBuilder(OrderRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $total = new Decimal(100);
        $dto = new OrderDTO(1, 1, "address-1", [], $total);

        $orderRepositoryMock
            ->method('getList')
            ->willReturn([
                $dto,
        ]);

        $productServiceMock = $this
            ->getMockBuilder(RemoteProductService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $addressServiceMock = $this
            ->getMockBuilder(AddressService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $service = new OrderService(
            $orderRepositoryMock,
            $productServiceMock,
            $addressServiceMock,
            new NullLogger(),
        );

        /** @var OrderDTO[] $result */
        $result = $service->getList();
        self::assertIsArray($result);
        self::assertCount(1, $result);
        self::assertSame($result[0]->id, 1);
        self::assertSame($result[0]->addressId, 1);
        self::assertSame($result[0]->address, "address-1");
        self::assertSame($result[0]->total, $total);
    }

    public function testCreate(): void
    {
        $orderRepositoryMock = $this
            ->getMockBuilder(OrderRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $total = new Decimal(3000);
        $order = new Order();
        $order->setAttribute('id', 1);
        $order->address_id = 1;
        $order->setAttribute('address', null);
        $order->setAttribute('orderProducts', []);
        $order->total = $total;

        $orderRepositoryMock
            ->method('save')
            ->willReturn($order);

        $productServiceMock = $this
            ->getMockBuilder(RemoteProductService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $productServiceMock
            ->method('getPriceById')
            ->willReturn(new Decimal(10));

        $addressServiceMock = $this
            ->getMockBuilder(AddressService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $addressServiceMock
            ->method('getById')
            ->willReturn(new AddressDTO(1, 'address-1'));

        $service = new OrderService(
            $orderRepositoryMock,
            $productServiceMock,
            $addressServiceMock,
            new NullLogger(),
        );

        $productsDTO = [
            new NewOrderProductDTO(1, 100),
            new NewOrderProductDTO(2, 100),
        ];

        $result = $service->create(1, $productsDTO);
        self::assertInstanceOf(OrderDTO::class, $result);
        self::assertSame($result->id, 1);
        self::assertSame($result->addressId, 1);
        self::assertEquals($result->total, $total);
    }

    public function testGetById(): void
    {
        $orderRepositoryMock = $this
            ->getMockBuilder(OrderRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $total = new Decimal(100);
        $order = new Order();
        $order->setAttribute('id', 1);
        $order->address_id = 1;
        $order->setAttribute('address', null);
        $order->setAttribute('orderProducts', []);
        $order->total = $total;

        $orderRepositoryMock
            ->method('getById')
            ->willReturn(
                $order,
            );

        $productServiceMock = $this
            ->getMockBuilder(RemoteProductService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $addressServiceMock = $this
            ->getMockBuilder(AddressService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $service = new OrderService(
            $orderRepositoryMock,
            $productServiceMock,
            $addressServiceMock,
            new NullLogger(),
        );

        $result = $service->getById(1);
        self::assertInstanceOf(OrderDTO::class, $result);
        self::assertSame($result->id, 1);
        self::assertSame($result->addressId, 1);
        self::assertEquals($result->total, $total);
    }

    public function testDeleteById(): void
    {
        $orderRepositoryMock = $this
            ->getMockBuilder(OrderRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $orderRepositoryMock
            ->method('deleteById');

        $productServiceMock = $this
            ->getMockBuilder(RemoteProductService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $addressServiceMock = $this
            ->getMockBuilder(AddressService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $service = new OrderService(
            $orderRepositoryMock,
            $productServiceMock,
            $addressServiceMock,
            new NullLogger(),
        );

        $service->deleteById(1);
        self::expectNotToPerformAssertions();
    }

    public function testUpdateById(): void
    {
        $orderRepositoryMock = $this
            ->getMockBuilder(OrderRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $total = new Decimal(100);
        $order = new Order();
        $order->setAttribute('id', 1);
        $order->address_id = 1;
        $order->setAttribute('address', null);
        $order->setAttribute('orderProducts', []);
        $order->total = $total;

        $orderRepositoryMock
            ->method('updateById')
            ->willReturn(
                $order,
            );

        $productServiceMock = $this
            ->getMockBuilder(RemoteProductService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $productServiceMock
            ->method('getPriceById')
            ->willReturn(new Decimal(1000));

        $addressServiceMock = $this
            ->getMockBuilder(AddressService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $addressServiceMock
            ->method('getById')
            ->willReturn(new AddressDTO(1, 'address-1'));

        $service = new OrderService(
            $orderRepositoryMock,
            $productServiceMock,
            $addressServiceMock,
            new NullLogger(),
        );

        $productsDTO = [
            new NewOrderProductDTO(1, 100),
        ];

        $result = $service->updateById(1, null, $productsDTO);
        self::assertInstanceOf(OrderDTO::class, $result);
        self::assertSame($result->id, 1);
        self::assertSame($result->addressId, 1);
        self::assertEquals($result->total, $total);
    }
}
