<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1;

use App\Contracts\LockAdapterInterface;
use App\Contracts\OrderServiceInterface;
use App\DTO\NewOrderProductDTO;
use App\Exceptions\OrderException;
use App\Http\Requests\CreateRequest;
use App\Http\Requests\UpdateRequest;
use Illuminate\Routing\Controller as BaseController;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Webmozart\Assert\Assert;

final class OrdersXMLController extends BaseController
{
    private const DEFAULT_LOCK_SECONDS = 5;

    public function __construct(
        private readonly OrderServiceInterface $orderService,
        private readonly LockAdapterInterface $lockAdapter,
        private readonly LoggerInterface $logger,
    ) {

    }

    /**
     * @OA\Get(
     *     path="/orders/web/v1/orders",
     *     tags={"web-orders"},
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *     )
     * )
     */
    public function list(): Response
    {
        $list = $this
            ->orderService
            ->getList();

        $orders = [];

        foreach ($list as $item) {
            $orders[] = $item->toArray();
        }

        // @phpstan-ignore-next-line
        return response()->xml([
                'orders' => $orders,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/orders/web/v1/orders",
     *     tags={"web-orders"},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="address_id",
     *                  type="integer",
     *                  description="Address",
     *                  example="1",
     *              ),
     *              @OA\Property(
     *                  property="products",
     *                  type="array",
     *                  minItems=1,
     *                  @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                        property="product_id",
     *                        type="integer",
     *                        description="Product",
     *                        example="1"
     *                     ),
     *                     @OA\Property(
     *                        property="count",
     *                        type="integer",
     *                        description="Count",
     *                        example="5",
     *                    ),
     *                 )
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *         )
     *     )
     * )
     */
    public function create(CreateRequest $request): Response
    {
        $clientIdfromJWT = 'UUID-1';

        $lock = $this
            ->lockAdapter
            ->lock($clientIdfromJWT, self::DEFAULT_LOCK_SECONDS);

        if (!$lock) {
            throw new HttpException(403, 'Forbidden');
        }

        $request->validated();

        $addressId = $request->get('address_id');
        Assert::integer($addressId);
        $products = $request->get('products');
        Assert::isArray($products);

        $productsCollection = [];

        foreach ($products as $product) {
            $productsCollection[] = new NewOrderProductDTO(
                $product['product_id'],
                $product['count'],
            );
        }

        try {
            $orderDTO = $this
                ->orderService
                ->create($addressId, $productsCollection);

            // @phpstan-ignore-next-line
            return response()->xml($orderDTO->toArray());
        } catch (OrderException $e) {
            $this
                ->logger
                ->error("create: " . $e->getMessage());

            throw new HttpException(400, 'Bad request');
        }
    }

    /**
     * @OA\Get(
     *     path="/orders/web/v1/orders/{id}",
     *     tags={"web-orders"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *      ),
     *      @OA\Response(
     *         response=200,
     *         description="success",
     *          @ OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="id",
     *                 type="integer",
     *             ),
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *             ),
     *              @OA\Property(
     *                  property="description",
     *                  type="string",
     *                  description="Description",
     *              ),
     *              @OA\Property(
     *                  property="price",
     *                  type="decimal",
     *                  description="Price",
     *              )
     *         )
     *     )
     * )
     */
    public function getOne(int $id): Response
    {
        try {
            $orderDTO = $this
                ->orderService
                ->getById($id);

            // @phpstan-ignore-next-line
            return response()->xml($orderDTO->toArray());
        } catch (OrderException $e) {
            $this
                ->logger
                ->error("getOne: " . $e->getMessage());

            throw new HttpException(400, 'Bad request');
        }
    }

    /**
     * @OA\Delete(
     *     path="/orders/web/v1/orders/{id}",
     *     tags={"web-orders"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="success"
     *     )
     * )
     */
    public function delete(int $id): Response
    {
        try {
            $this
                ->orderService
                ->deleteById($id);
        } catch (OrderException $e) {
            $this
                ->logger
                ->error("delete: " . $e->getMessage());

            throw new HttpException(400, 'Bad request');
        }

        // @phpstan-ignore-next-line
        return response()->xml("");
    }

    /**
     * @OA\Patch(
     *     path="/orders/web/v1/orders/{id}",
     *     tags={"web-orders"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *      ),
     *      @OA\RequestBody(
     *          required=false,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="address_id",
     *                  type="integer",
     *                  description="Address",
     *                  example="1",
     *              ),
     *              @OA\Property(
     *                  property="products",
     *                  type="array",
     *                  minItems=1,
     *                  @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                        property="product_id",
     *                        type="integer",
     *                        description="Product",
     *                        example="1"
     *                     ),
     *                     @OA\Property(
     *                        property="count",
     *                        type="integer",
     *                        description="Count",
     *                        example="5",
     *                    ),
     *                 )
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent()
     *     )
     * )
     */
    public function update(int $id, UpdateRequest $request): Response
    {
        $clientIdfromJWT = 'UUID-1';

        $lock = $this
            ->lockAdapter
            ->lock($clientIdfromJWT, self::DEFAULT_LOCK_SECONDS);

        if (!$lock) {
            throw new HttpException(403, 'Forbidden');
        }

        $request->validated();

        $addressId = $request->get('address_id');

        if ($addressId !== null) {
            Assert::integer($addressId);
        }

        $products = $request->get('products', []);
        Assert::isArray($products);

        $productsCollection = [];

        foreach ($products as $product) {
            $productsCollection[] = new NewOrderProductDTO(
                $product['product_id'],
                $product['count'],
            );
        }

        try {
            $orderDTO = $this
                ->orderService
                ->updateById($id, $addressId, $productsCollection);

            // @phpstan-ignore-next-line
            return response()->xml($orderDTO->toArray());
        } catch (OrderException $e) {
            $this
                ->logger
                ->error("update: " . $e->getMessage());

            throw new HttpException(400, 'Bad request');
        }
    }
}
