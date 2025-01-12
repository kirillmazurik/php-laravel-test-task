<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\OrderRepositoryInterface;
use App\Contracts\OrderSearchServiceInterface;
use App\Contracts\ProductServiceInterface;
use App\DTO\OrderDTO;
use App\Exceptions\OrderException;
use Exception;
use Psr\Log\LoggerInterface;

/**
 * @final
 */
class OrderSearchService implements OrderSearchServiceInterface
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly ProductServiceInterface $productService,
        private readonly LoggerInterface $logger,
    ) {

    }

    /**
     * @return OrderDTO[]
     */
    public function search(string $name): array
    {
        try {
            $productIds = $this
                ->productService
                ->getProductsByName($name);

            return $this
                    ->orderRepository
                    ->getListByIds($productIds);
        } catch (Exception $e) {
            $this
                ->logger
                ->error("OrderService: search: " . $e->getMessage());

            throw new OrderException($e->getMessage());
        }
    }
}
