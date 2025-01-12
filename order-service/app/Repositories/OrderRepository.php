<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\OrderRepositoryInterface;
use App\DTO\NewOrderDTO;
use App\DTO\NewOrderProductDTO;
use App\DTO\NewProductDTO;
use App\DTO\OrderDTO;
use App\DTO\UpdatedOrderDTO;
use App\Models\Order;
use App\Models\OrderProducts;
use Illuminate\Support\Facades\DB;
use PDOException;

/**
 * @final
 */
class OrderRepository implements OrderRepositoryInterface
{
    /**
     * @return OrderDTO[]
     */
    public function getList(): array
    {
        $orders = Order::query()->get();

        $result = [];

        /** @var Order $order */
        foreach ($orders as $order) {
            $result[] = $order->toDTO();
        }

        return $result;
    }

    public function save(NewOrderDTO $orderDTO): Order
    {
        $order = new Order();
        $order->address_id = $orderDTO->addressId;
        $order->total = $orderDTO->total;

        $orderProducts = [];

        /** @var NewProductDTO $productDTO */
        foreach ($orderDTO->productsDTO as $productDTO) {
            $orderProduct = new OrderProducts();
            $orderProduct->product_id = $productDTO->productId;
            $orderProduct->count = $productDTO->count;
            $orderProduct->sell_price = $productDTO->sellPrice;
            $orderProduct->total = $productDTO->total;

            $orderProducts[] = $orderProduct;
        }

        DB::beginTransaction();

        try {
            $order->save();

            foreach ($orderProducts as $orderProduct) {
                $orderProduct->order_id = $order->id;
                $orderProduct->save();
            }


            DB::commit();
        } catch (PDOException $e) {
            DB::rollBack();

            throw $e;
        }

        return $order;
    }

    public function getById(int $id): Order
    {
        /** @var Order|null $order */
        $order = Order::query()->find($id);

        if ($order === null) {
            throw new PDOException("Cannot find an order");
        }

        return $order;
    }

    public function deleteById(int $id): void
    {
        DB::beginTransaction();

        try {
            /** @var Order|null $order */
            $order = Order::query()->find($id);

            if ($order === null) {
                throw new PDOException("Cannot delete an order");
            }

            foreach ($order->orderProducts as $orderProducts) {
                $orderProducts->delete();
            }

            $order->delete();

            DB::commit();
        } catch (PDOException $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function updateById(int $id, UpdatedOrderDTO $dto): Order
    {
        /** @var Order|null $order */
        $order = Order::query()->find($id);

        if ($order === null) {
            throw new PDOException("Cannot update an order");
        }

        if ($dto->addressId !== null) {
            $order->address_id = $dto->addressId;
        }

        if ($dto->total !== null && $dto->total->isPositive()) {
            $order->total = $dto->total;
        }

        $orderProducts = [];

        if (count($dto->productsDTO) !== 0) {
            /** @var NewProductDTO $productDTO */
            foreach ($dto->productsDTO as $productDTO) {
                $orderProduct = new OrderProducts();
                $orderProduct->product_id = $productDTO->productId;
                $orderProduct->count = $productDTO->count;
                $orderProduct->sell_price = $productDTO->sellPrice;
                $orderProduct->total = $productDTO->total;

                $orderProducts[] = $orderProduct;
            }
        }

        DB::beginTransaction();

        try {
            $order->save();

            if (count($orderProducts) !== 0) {
                foreach ($order->orderProducts as $oldProduct) {
                    $oldProduct->delete();
                }

                foreach ($orderProducts as $orderProduct) {
                    $orderProduct->order_id = $order->id;
                    $orderProduct->save();
                }
            }

            DB::commit();
        } catch (PDOException $e) {
            DB::rollBack();

            throw $e;
        }

        $order->refresh();

        return $order;
    }

    /**
     * @return OrderDTO[]
     */
    public function searchByName(string $name): array
    {
        $orders = Order::query()
            ->where("name", "LIKE", "%" . $name . "%")
            ->get();

        $result = [];

        /** @var Order $order */
        foreach ($orders as $order) {
            $result[] = $order->toDTO();
        }

        return $result;
    }

    /**
     * @param int[] $ids
     * @return OrderDTO[]
     */
    public function getListByIds(array $ids): array
    {
        $orderProducts = OrderProducts::query()
            ->whereIn('product_id', $ids)
            ->get();

        $orderIds = [];

        /** @var OrderProducts $orderProduct */
        foreach ($orderProducts as $orderProduct) {
            $orderIds[] = $orderProduct->order_id;
        }

        $orderIds = array_unique($orderIds);

        $orders = Order::query()
            ->whereIn('id', $orderIds)
            ->get();

        $result = [];

        /** @var Order $order */
        foreach ($orders as $order) {
            $result[] = $order->toDTO();
        }

        return $result;
    }
}
