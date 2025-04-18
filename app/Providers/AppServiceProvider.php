<?php

namespace App\Providers;

use App\Interfaces\PositionListServiceInterface;
use App\Interfaces\UserServiceInterface;
use App\Services\PositionListService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PositionListServiceInterface::class, PositionListService::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
