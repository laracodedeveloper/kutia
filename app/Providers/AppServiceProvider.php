<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kutia\Laravel\Modules\Interfaces\PackageInterface;
use Kutia\Laravel\Modules\Packagist;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(PackageInterface::class,Packagist::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
