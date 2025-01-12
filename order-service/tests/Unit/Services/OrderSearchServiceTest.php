<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\DTO\OrderDTO;
use App\Repositories\OrderRepository;
use App\Services\OrderSearchService;
use App\Services\RemoteProductService;
use Decimal\Decimal;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class OrderSearchServiceTest extends TestCase
{
    public function testSerach(): void
    {
        $orderRepositoryMock = $this
            ->getMockBuilder(OrderRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $total = new Decimal(100);
        $dto1 = new OrderDTO(1, 1, "address-1", [], $total);
        $dto2 = new OrderDTO(2, 2, "address-2", [], $total);

        $orderRepositoryMock
            ->method('getListByIds')
            ->willReturn([
                $dto1,
                $dto2,
        ]);

        $productServiceMock = $this
            ->getMockBuilder(RemoteProductService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $productServiceMock
            ->method('getProductsByName')
            ->willReturn([1,2]);

        $service = new OrderSearchService(
            $orderRepositoryMock,
            $productServiceMock,
            new NullLogger(),
        );

        /** @var OrderDTO[] $result */
        $result = $service->search("order");
        self::assertIsArray($result);
        self::assertCount(2, $result);
        self::assertSame($result[0]->id, 1);
        self::assertSame($result[1]->id, 2);
    }
}
