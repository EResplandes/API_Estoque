<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\TransferService;

class TransferServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(TransferService::class, function ($app) {
            return new TransferService();
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
