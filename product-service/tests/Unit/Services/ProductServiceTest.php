<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\DTO\ProductDTO;
use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Services\ProductService;
use Decimal\Decimal;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class ProductServiceTest extends TestCase
{
    public function testGetList(): void
    {
        $productRepositoryMock = $this
            ->getMockBuilder(ProductRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $price = new Decimal(100);
        $dto = new ProductDTO(1, "name-1", "desc-1", $price);

        $productRepositoryMock
            ->method('getList')
            ->willReturn([
                $dto,
        ]);

        $service = new ProductService($productRepositoryMock, new NullLogger());

        /** @var []ProductDTO $result * */
        $result = $service->getList();
        self::assertIsArray($result);
        self::assertCount(1, $result);
        self::assertSame($result[0]->id, 1);
        self::assertSame($result[0]->name, "name-1");
        self::assertSame($result[0]->description, "desc-1");
        self::assertSame($result[0]->price, $price);
    }

    public function testCreate(): void
    {
        $productRepositoryMock = $this
            ->getMockBuilder(ProductRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $name = "new name";
        $description = "new desc";
        $price = new Decimal(100);

        $product = new Product();
        $product->id = 1;
        $product->name = $name;
        $product->description = $description;
        $product->price = $price;

        $productRepositoryMock
            ->method('save')
            ->willReturn($product);

        $service = new ProductService($productRepositoryMock, new NullLogger());

        $result = $service->create($name, $description, $price);
        self::assertInstanceOf(ProductDTO::class, $result);
        self::assertSame($result->id, 1);
        self::assertSame($result->name, "new name");
        self::assertSame($result->description, "new desc");
        self::assertEquals($result->price, $price);
    }

    public function testGetById(): void
    {
        $productRepositoryMock = $this
            ->getMockBuilder(ProductRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $name = "name";
        $description = "desc";
        $price = new Decimal(100);

        $product = new Product();
        $product->id = 1;
        $product->name = $name;
        $product->description = $description;
        $product->price = $price;

        $productRepositoryMock
            ->method('getById')
            ->willReturn(
                $product,
            );

        $service = new ProductService($productRepositoryMock, new NullLogger());

        $result = $service->getById(1);
        self::assertInstanceOf(ProductDTO::class, $result);
        self::assertSame($result->id, 1);
        self::assertSame($result->name, "name");
        self::assertSame($result->description, "desc");
        self::assertEquals($result->price, $price);
    }

    public function testDeleteById(): void
    {
        $productRepositoryMock = $this
            ->getMockBuilder(ProductRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $productRepositoryMock
            ->method('deleteById');

        $service = new ProductService($productRepositoryMock, new NullLogger());

        $service->deleteById(1);
        self::expectNotToPerformAssertions();
    }

    public function testUpdateById(): void
    {
        $productRepositoryMock = $this
            ->getMockBuilder(ProductRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $name = "name-2";
        $description = "desc-2";
        $price = new Decimal(100);

        $product = new Product();
        $product->id = 1;
        $product->name = $name;
        $product->description = $description;
        $product->price = $price;

        $productRepositoryMock
            ->method('updateById')
                        ->willReturn(
                            $product,
                        );


        $service = new ProductService($productRepositoryMock, new NullLogger());

        $result = $service->updateById(1, "name-2", "desc-2", null);
        self::assertInstanceOf(ProductDTO::class, $result);
        self::assertSame($result->id, 1);
        self::assertSame($result->name, "name-2");
        self::assertSame($result->description, "desc-2");
        self::assertEquals($result->price, $price);
    }

    public function testSerach(): void
    {
        $productRepositoryMock = $this
            ->getMockBuilder(ProductRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $price = new Decimal(100);
        $dto1 = new ProductDTO(1, "name-1", "desc-1", $price);
        $dto2 = new ProductDTO(2, "name-2", "desc-2", $price);

        $productRepositoryMock
            ->method('searchByName')
            ->willReturn([
                $dto1,
                $dto2,
        ]);

        $service = new ProductService($productRepositoryMock, new NullLogger());

        /** @var []ProductDTO $result * */
        $result = $service->search("product");
        self::assertIsArray($result);
        self::assertCount(2, $result);
        self::assertSame($result[0]->id, 1);
        self::assertSame($result[1]->id, 2);
    }
}
