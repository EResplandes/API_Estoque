<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\StockService;

class StockServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(StockService::class, function ($app){
            return new StockService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {s
        //
    }
}
