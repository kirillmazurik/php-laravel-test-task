<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\AddressServiceInterface;
use App\Contracts\OrderRepositoryInterface;
use App\Contracts\OrderServiceInterface;
use App\Contracts\ProductServiceInterface;
use App\DTO\NewOrderDTO;
use App\DTO\NewOrderProductDTO;
use App\DTO\NewProductDTO;
use App\DTO\OrderDTO;
use App\DTO\UpdatedOrderDTO;
use App\Exceptions\AddressException;
use App\Exceptions\OrderException;
use Decimal\Decimal;
use Exception;
use PDOException;
use Psr\Log\LoggerInterface;
use Webmozart\Assert\Assert;

/**
 * @final
 */
class OrderService implements OrderServiceInterface
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly ProductServiceInterface $productService,
        private readonly AddressServiceInterface $addressService,
        private readonly LoggerInterface $logger,
    ) {

    }

    /**
     * @return OrderDTO[]
     */
    public function getList(): array
    {
        return $this
                ->orderRepository
                ->getList();
    }

    /**
     * @param NewOrderProductDTO[] $productsDTO
     */
    public function create(
        int $adddressId,
        array $productsDTO,
    ): OrderDTO {
        try {
            $this
                ->addressService
                ->getById($adddressId);

            $orderProducts = [];
            $total = new Decimal(0);

            foreach ($productsDTO as $productDTO) {
                $productPrice = $this
                    ->productService
                    ->getPriceById($productDTO->productId);

                $orderProducts[] = new NewProductDTO($productDTO->productId, $productDTO->count, $productPrice);

                $total += $total->add($productPrice->mul($productDTO->count));
            }

            Assert::isInstanceOf($total, Decimal::class);
            $orderDTO = new NewOrderDTO($adddressId, $total, $orderProducts);

            $order = $this
                ->orderRepository
                ->save($orderDTO);

            return $order->toDTO();
        } catch (AddressException $e) {
            $this
                ->logger
                ->error("OrderService: create: address not found: " . $e->getMessage(), [
                    'address_id' => $adddressId,
            ]);

            throw new OrderException($e->getMessage());
        } catch (PDOException $e) {
            $this
                ->logger
                ->error("OrderService: create: " . $e->getMessage(), [
                    'adddress_id' => $adddressId,
            ]);

            throw new OrderException($e->getMessage());
        } catch (Exception $e) {
            $this
                ->logger
                ->error("OrderService: create: " . $e->getMessage());

            throw new OrderException('Product not found in the Product service');
        }
    }

    public function getById(int $id): OrderDTO
    {
        try {
            $order = $this
                ->orderRepository
                ->getById($id);

            return $order->toDTO();
        } catch (PDOException $e) {
            $this
                ->logger
                ->error("OrderService: getById: " . $e->getMessage(), [
                    'id' => $id,
            ]);

            throw new OrderException($e->getMessage());
        }
    }

    public function deleteById(int $id): void
    {
        try {
            $this
                ->orderRepository
                ->deleteById($id);
        } catch (PDOException $e) {
            $this
                ->logger
                ->error("OrderService: deleteById: " . $e->getMessage(), [
                    'id' => $id,
            ]);

            throw new OrderException($e->getMessage());
        }
    }

    /**
     * @param NewOrderProductDTO[] $productsDTO
     */
    public function updateById(
        int $id,
        ?int $adddressId = null,
        array $productsDTO = [],
    ): OrderDTO {
        try {
            if ($adddressId !== null) {
                $this
                    ->addressService
                    ->getById($adddressId);
            }

            $orderProducts = [];
            $total = new Decimal(0);

            if (count($productsDTO) !== 0) {
                foreach ($productsDTO as $productDTO) {
                    $productPrice = $this
                        ->productService
                        ->getPriceById($productDTO->productId);

                    $orderProducts[] = new NewProductDTO($productDTO->productId, $productDTO->count, $productPrice);

                    $total += $total->add($productPrice->mul($productDTO->count));
                }
            }

            Assert::isInstanceOf($total, Decimal::class);
            $orderDTO = new UpdatedOrderDTO($adddressId, $total, $orderProducts);

            $order = $this
                ->orderRepository
                ->updateById($id, $orderDTO);

            return $order->toDTO();
        } catch (AddressException $e) {
            $this
                ->logger
                ->error("OrderService: updateById: address not found: " . $e->getMessage(), [
                    'address_id' => $adddressId,
            ]);

            throw new OrderException($e->getMessage());
        } catch (PDOException $e) {
            $this
                ->logger
                ->error("OrderService: updateById: " . $e->getMessage(), [
                    'adddress_id' => $adddressId,
            ]);

            throw new OrderException($e->getMessage());
        } catch (Exception $e) {
            $this
                ->logger
                ->error("OrderService: updateById: " . $e->getMessage());

            throw new OrderException($e->getMessage());
        }
    }
}
