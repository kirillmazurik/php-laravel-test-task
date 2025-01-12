<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Adapters\CacheAdapter;
use App\Services\HttpClient;
use App\Services\RemoteProductService;
use Decimal\Decimal;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use stdClass;

class RemoteProductServiceTest extends TestCase
{
    public function testGetPriceById(): void
    {
        $httpMock = $this
            ->getMockBuilder(HttpClient::class)
            ->disableOriginalConstructor()
            ->getMock();

        $stdClass = new stdClass();
        $stdClass->data = new stdClass();
        $stdClass->data->price = 100;

        $httpMock
            ->method('getRequest')
            ->willReturn($stdClass);

        $cacheMock = $this
            ->getMockBuilder(CacheAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $cacheMock
            ->method('set')
            ->willReturn(true);

        $cacheMock
            ->method('get')
            ->willReturn(null);

        $service = new RemoteProductService(
            $httpMock,
            $cacheMock,
            new NullLogger(),
        );

        $result = $service->getPriceById(1);
        self::assertInstanceOf(Decimal::class, $result);
        self::assertSame($result->toFloat(), 100.0);
    }

    public function testGetProductsByName(): void
    {
        $httpMock = $this
            ->getMockBuilder(HttpClient::class)
            ->disableOriginalConstructor()
            ->getMock();

        $datum = new stdClass();
        $datum->id = 10;

        $stdClass = new stdClass();
        $stdClass->data = [
            $datum,
        ];

        $httpMock
            ->method('getRequest')
            ->willReturn($stdClass);

        $cacheMock = $this
            ->getMockBuilder(CacheAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $cacheMock
            ->method('set')
            ->willReturn(true);

        $cacheMock
            ->method('get')
            ->willReturn(null);

        $service = new RemoteProductService(
            $httpMock,
            $cacheMock,
            new NullLogger(),
        );

        $result = $service->getProductsByName('product');
        self::assertIsArray($result);
        self::assertCount(1, $result);
        self::assertSame($result[0], 10);
    }
}
