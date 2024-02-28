<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ReportsService;

class ReportsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ReportsService::class, function ($app) {
            return new ReportsService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
