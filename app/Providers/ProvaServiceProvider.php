<?php

namespace App\Providers;

use App\Models\Prova;
use Illuminate\Support\ServiceProvider;

class ProvaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->when('App\Repositories\ProvaRepository')
            ->needs('App\Models\Prova')
            ->give(function () {
                return new Prova();
            });

        $this->app->when('App\Services\ProvaService')
            ->needs('App\Interfaces\IProvaRepository')
            ->give('App\Repositories\ProvaRepository');

        $this->app->when('App\Services\ProvaService')
            ->needs('App\Interfaces\ICorredorService')
            ->give('App\Services\CorredorService');

        $this->app->when('App\Http\Controllers\Api\ProvaController')
            ->needs('App\Interfaces\IProvaService')
            ->give('App\Services\ProvaService');
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
