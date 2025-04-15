<?php

namespace App\Providers;

use App\Interfaces\PositionListServiceInterface;
use App\Services\PositionListService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PositionListServiceInterface::class, PositionListService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
