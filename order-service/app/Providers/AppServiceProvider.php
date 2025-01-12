<?php

namespace App\Providers;

use App\Adapters\CacheAdapter;
use App\Adapters\LockAdapter;
use App\Contracts\AddressRepositoryInterface;
use App\Contracts\AddressServiceInterface;
use App\Contracts\CacheAdapterInterface;
use App\Contracts\HttpClientFactoryInterface;
use App\Contracts\LockAdapterInterface;
use App\Contracts\OrderRepositoryInterface;
use App\Contracts\OrderSearchServiceInterface;
use App\Contracts\OrderServiceInterface;
use App\Contracts\ProductServiceInterface;
use App\Repositories\AddressRepository;
use App\Repositories\OrderRepository;
use App\Services\AddressService;
use App\Services\HttpClient;
use App\Services\OrderSearchService;
use App\Services\OrderService;
use App\Services\RemoteProductService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this
                ->app
                ->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this
                ->app
                ->bind(OrderServiceInterface::class, OrderService::class);
        $this
                ->app
                ->bind(OrderSearchServiceInterface::class, OrderSearchService::class);
        $this
                ->app
                ->bind(AddressRepositoryInterface::class, AddressRepository::class);
        $this
                ->app
                ->bind(AddressServiceInterface::class, AddressService::class);
        $this
                ->app
                ->bind(ProductServiceInterface::class, RemoteProductService::class);
        $this
                ->app
                ->bind(HttpClientFactoryInterface::class, HttpClient::class);
        $this
                ->app
                ->bind(CacheAdapterInterface::class, CacheAdapter::class);
        $this
                ->app
                ->bind(LockAdapterInterface::class, LockAdapter::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
