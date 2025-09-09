<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Cache frequently accessed relationships
        \Illuminate\Database\Eloquent\Model::preventLazyLoading(!app()->isProduction());
         Model::preventLazyLoading(false);
    }
}
