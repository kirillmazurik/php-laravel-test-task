<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1;

use App\Contracts\LockAdapterInterface;
use App\Contracts\OrderServiceInterface;
use App\DTO\NewOrderProductDTO;
use App\Exceptions\OrderException;
use App\Http\Requests\CreateRequest;
use App\Http\Requests\UpdateRequest;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Routing\Controller as BaseController;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Webmozart\Assert\Assert;

/**
 * @OA\Info(
 *      title="Test task",
 *      version="1",
 * )
 */
final class OrdersJSONController extends BaseController
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
     *     path="/orders/api/v1/orders",
     *     tags={"api-orders"},
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *     )
     * )
     */
    public function list(): JsonResource
    {
        $list = $this
            ->orderService
            ->getList();

        return new JsonResource($list);
    }

    /**
     * @OA\Post(
     *     path="/orders/api/v1/orders",
     *     tags={"api-orders"},
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
    public function create(CreateRequest $request): JsonResource
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

            return new JsonResource($orderDTO);
        } catch (OrderException $e) {
            $this
                ->logger
                ->error("create: " . $e->getMessage());

            throw new HttpException(400, 'Bad request: ' . $e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/orders/api/v1/orders/{id}",
     *     tags={"api-orders"},
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
    public function getOne(int $id): JsonResource
    {
        try {
            $orderDTO = $this
                ->orderService
                ->getById($id);

            return new JsonResource($orderDTO);
        } catch (OrderException $e) {
            $this
                ->logger
                ->error("getOne: " . $e->getMessage());

            throw new HttpException(400, 'Bad request');
        }
    }

    /**
     * @OA\Delete(
     *     path="/orders/api/v1/orders/{id}",
     *     tags={"api-orders"},
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
    public function delete(int $id): JsonResource
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

        return new JsonResource(null);
    }

    /**
     * @OA\Patch(
     *     path="/orders/api/v1/orders/{id}",
     *     tags={"api-orders"},
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
    public function update(int $id, UpdateRequest $request): JsonResource
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

            return new JsonResource($orderDTO);
        } catch (OrderException $e) {
            $this
                ->logger
                ->error("update: " . $e->getMessage());

            throw new HttpException(400, 'Bad request');
        }
    }
}
