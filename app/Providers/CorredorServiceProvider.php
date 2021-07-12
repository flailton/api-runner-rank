<?php

namespace App\Providers;

use App\Models\Corredor;
use Illuminate\Support\ServiceProvider;

class CorredorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->when('App\Repositories\CorredorRepository')
            ->needs('App\Models\Corredor')
            ->give(function () {
                return new Corredor();
            });

        $this->app->when('App\Services\CorredorService')
            ->needs('App\Interfaces\ICorredorRepository')
            ->give('App\Repositories\CorredorRepository');

        $this->app->when('App\Http\Controllers\Api\CorredorController')
            ->needs('App\Interfaces\ICorredorService')
            ->give('App\Services\CorredorService');
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
