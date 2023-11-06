<?php

namespace App\Providers;

use App\Services\MultichainService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('multichainService', function ($app) {
            return (new MultichainService())->setBlockChain(config('multichain.blockchain_name'));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
