<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\RequestsService;

class StockServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(RequestsService::class, function ($app){
            return new RequestsService();
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
