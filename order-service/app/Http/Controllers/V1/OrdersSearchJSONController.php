<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1;

use App\Contracts\OrderSearchServiceInterface;
use App\Exceptions\OrderException;
use App\Http\Requests\SearchRequest;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Routing\Controller as BaseController;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Webmozart\Assert\Assert;

final class OrdersSearchJSONController extends BaseController
{
    public function __construct(
        private readonly OrderSearchServiceInterface $orderService,
        private readonly LoggerInterface $logger,
    ) {

    }

    /**
     * @OA\Get(
     *     path="/orders/api/v1/orders/search",
     *     tags={"api-orders"},
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\Items(
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
    public function search(SearchRequest $request): JsonResource
    {
        $request->validated();

        $name = $request->get('name');
        Assert::string($name);

        try {
            $list = $this
                ->orderService
                ->search(trim($name));

            return new JsonResource($list);
        } catch (OrderException $e) {
            $this
                ->logger
                ->error("search: " . $e->getMessage());

            throw new HttpException(400, 'Bad request');
        }
    }
}
