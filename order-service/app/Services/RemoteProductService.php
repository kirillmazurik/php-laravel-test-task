<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\CacheAdapterInterface;
use App\Contracts\HttpClientFactoryInterface;
use App\Contracts\ProductServiceInterface;
use Decimal\Decimal;
use Exception;
use Psr\Log\LoggerInterface;
use Webmozart\Assert\Assert;

/**
 * @final
 */
class RemoteProductService implements ProductServiceInterface
{
    private const DEFAULT_SECONDS = 60;
    private const URL_PRODUCT_BY_ID = 'products/api/v1/products/';
    private const URL_SEARCH_BY_NAME = 'products/api/v1/products/search?name=';
    private const PRODUCT_PRICE_PREFIX = 'product_price_';
    private const PRODUCT_NAME_PREFIX = 'product_name_';

    public function __construct(
        private readonly HttpClientFactoryInterface $httpClient,
        private readonly CacheAdapterInterface $cacheAdapter,
        private readonly LoggerInterface $logger,
    ) {

    }

    public function getPriceById(int $id): Decimal
    {
        $price = $this
            ->cacheAdapter
            ->get(self::PRODUCT_PRICE_PREFIX . $id);

        if ($price) {
            Assert::isInstanceOf($price, Decimal::class);

            return $price;
        }

        $data = $this
            ->httpClient
            ->getRequest(self::URL_PRODUCT_BY_ID . $id);

        $newPrice = new Decimal($data->data->price);

        $this
            ->cacheAdapter
            ->set(self::PRODUCT_PRICE_PREFIX . $id, $newPrice, self::DEFAULT_SECONDS);

        return $newPrice;
    }

    /**
     * @return int[]
     */
    public function getProductsByName(string $name): array
    {
        $ids = $this
            ->cacheAdapter
            ->get(self::PRODUCT_NAME_PREFIX . $name);

        if (is_array($ids) && count($ids)) {
            return $ids;
        }

        $data = $this
            ->httpClient
            ->getRequest(self::URL_SEARCH_BY_NAME . $name);

        if (!is_array($data->data)) {
            $this
                ->logger
                ->error("RemoteProductService: getProductsByName: wrong data");

            throw new Exception('Something wwent wrong');
        }

        $newIds = [];

        foreach ($data->data as $datum) {
            $newIds[] = $datum->id;
        }

        if (count($newIds)) {
            $this
                ->cacheAdapter
                ->set(self::PRODUCT_NAME_PREFIX . $name, $newIds, self::DEFAULT_SECONDS);
        }

        return $newIds;
    }
}
