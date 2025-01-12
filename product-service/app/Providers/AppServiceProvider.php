<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\ProductRepositoryInterface;
use App\Contracts\ProductServiceInterface;
use App\Repositories\ProductRepository;
use App\Services\ProductService;

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
                ->bind(ProductRepositoryInterface::class, ProductRepository::class);

        $this
                ->app
                ->bind(ProductServiceInterface::class, ProductService::class);
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
