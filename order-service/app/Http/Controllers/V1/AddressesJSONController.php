<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1;

use App\Contracts\AddressServiceInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Routing\Controller as BaseController;

final class AddressesJSONController extends BaseController
{
    public function __construct(
        private readonly AddressServiceInterface $addressService,
    ) {

    }

    /**
     * @OA\Get(
     *     path="/orders/api/v1/addresses",
     *     tags={"api-addresses"},
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
    public function list(): JsonResource
    {
        $list = $this
            ->addressService
            ->getList();

        return new JsonResource($list);
    }
}
