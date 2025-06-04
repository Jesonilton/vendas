<?php

namespace App\Providers;

use App\Services\SaleService;
use App\Services\UserService;
use App\Services\SellerService;
use App\Services\CommissionService;
use Illuminate\Support\ServiceProvider;
use App\Services\Contracts\SaleServiceInterface;
use App\Services\Contracts\UserServiceInterface;
use App\Services\Contracts\SellerServiceInterface;
use App\Services\Contracts\CommissionServiceInterface;

class DependencyInjectionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(SaleServiceInterface::class, SaleService::class);
        $this->app->bind(CommissionServiceInterface::class, CommissionService::class);
        $this->app->bind(SellerServiceInterface::class, SellerService::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
