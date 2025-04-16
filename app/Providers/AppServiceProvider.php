<?php

namespace App\Providers;

use App\Interfaces\PositionListServiceInterface;
use App\Interfaces\UserListServiceInterface;
use App\Services\PositionListService;
use App\Services\UserListService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PositionListServiceInterface::class, PositionListService::class);
        $this->app->bind(UserListServiceInterface::class, UserListService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
