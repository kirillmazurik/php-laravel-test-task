<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1;

use App\Contracts\ProductServiceInterface;
use App\Exceptions\ProductException;
use App\Http\Requests\CreateRequest;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\UpdateRequest;
use Decimal\Decimal;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Routing\Controller as BaseController;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Webmozart\Assert\Assert;

use function response;

final class ProductsXMLController extends BaseController
{
    public function __construct(
        private readonly ProductServiceInterface $productService,
        private readonly LoggerInterface $logger,
    ) {

    }

    /**
     * @OA\Get(
     *     path="/products/web/v1/products",
     *     tags={"web-products"},
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
    public function list(): Response
    {
        $list = $this
            ->productService
            ->getList();

        $products = [];

        foreach ($list as $item) {
            $products[] = $item->toArray();
        }

        // @phpstan-ignore-next-line
        return response()->xml([
                'products' => $products,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/products/web/v1/products",
     *     tags={"web-products"},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="name",
     *                  type="string",
     *                  description="Name",
     *                  example="product name 1",
     *              ),
     *              @OA\Property(
     *                  property="description",
     *                  type="string",
     *                  description="Description",
     *                  example="product description 1",
     *              ),
     *              @OA\Property(
     *                  property="price",
     *                  type="decimal",
     *                  description="Price",
     *                  example="111.01",
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
        $request->validated();

        $name = $request->get('name');
        Assert::string($name);
        $description = $request->get('description');
        Assert::string($description);

        $price = $request->get('price');
        Assert::numeric($price);
        $price = new Decimal((string)$price);

        try {
            $productDTO = $this
                ->productService
                ->create($name, $description, $price);

            // @phpstan-ignore-next-line
            return response()->xml($productDTO->toArray());
        } catch (ProductException $e) {
            $this
                ->logger
                ->error("create: " . $e->getMessage());

            throw new HttpException(400, 'Bad request');
        }
    }

    /**
     * @OA\Get(
     *     path="/products/web/v1/products/{id}",
     *     tags={"web-products"},
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
     *         description="success",
     *          @OA\JsonContent(
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
            $productDTO = $this
                ->productService
                ->getById($id);

            // @phpstan-ignore-next-line
            return response()->xml($productDTO->toArray());
        } catch (ProductException $e) {
            $this
                ->logger
                ->error("getOne: " . $e->getMessage());

            throw new HttpException(400, 'Bad request');
        }
    }

    /**
     * @OA\Delete(
     *     path="/products/web/v1/products/{id}",
     *     tags={"web-products"},
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
                ->productService
                ->deleteById($id);

            // @phpstan-ignore-next-line
            return response()->xml("");
        } catch (ProductException $e) {
            $this
                ->logger
                ->error("delete: " . $e->getMessage());

            throw new HttpException(400, 'Bad request');
        }
    }

    /**
     * @OA\Patch(
     *     path="/products/web/v1/products/{id}",
     *     tags={"web-products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *      ),
     *     @OA\RequestBody(
     *          required=false,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="name",
     *                  type="string",
     *                  description="Name",
     *                  example="updated product name 1",
     *              ),
     *              @OA\Property(
     *                  property="description",
     *                  type="string",
     *                  description="Description",
     *                  example="updated product name 1",
     *              ),
     *              @OA\Property(
     *                  property="price",
     *                  type="decimal",
     *                  description="Price",
     *                  example="222",
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
        $request->validated();

        $name = $request->get('name', "");
        Assert::string($name);
        $description = $request->get('description', "");
        Assert::string($description);

        if (!empty($request->get('price'))) {
            $price =  $request->get('price');
            Assert::string($price);
            $price = new Decimal($price);
        } else {
            $price = null;
        }

        try {
            $productDTO = $this
                ->productService
                ->updateById($id, $name, $description, $price);

            // @phpstan-ignore-next-line
            return response()->xml($productDTO->toArray());
        } catch (ProductException $e) {
            $this
                ->logger
                ->error("update: " . $e->getMessage());

            throw new HttpException(400, 'Bad request');
        }
    }

    /**
     * @OA\Get(
     *     path="/products/web/v1/products/search",
     *     tags={"web-products"},
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
    public function search(SearchRequest $request): Response
    {
        $request->validated();

        $name = $request->get('name');
        Assert::string($name);

        $list = $this
            ->productService
            ->search(trim($name));

        $products = [];

        foreach ($list as $item) {
            $products[] = $item->toArray();
        }

        // @phpstan-ignore-next-line
        return response()->xml([
                'products' => $products,
        ]);
    }
}
