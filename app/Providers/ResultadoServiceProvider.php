<?php

namespace App\Providers;

use App\Models\Resultado;
use Illuminate\Support\ServiceProvider;

class ResultadoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->when('App\Repositories\ResultadoRepository')
            ->needs('App\Models\Resultado')
            ->give(function () {
                return new Resultado();
            });

        $this->app->when('App\Services\ResultadoService')
            ->needs('App\Interfaces\IResultadoRepository')
            ->give('App\Repositories\ResultadoRepository');

        $this->app->when('App\Http\Controllers\Api\ResultadoController')
            ->needs('App\Interfaces\IResultadoService')
            ->give('App\Services\ResultadoService');

        $this->app->when('App\Services\ResultadoService')
            ->needs('App\Interfaces\IProvaService')
            ->give('App\Services\ProvaService');

        $this->app->when('App\Services\ResultadoService')
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
