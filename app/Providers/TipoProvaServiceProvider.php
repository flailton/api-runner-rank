<?php

namespace App\Providers;

use App\Models\TipoProva;
use Illuminate\Support\ServiceProvider;

class TipoProvaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->when('App\Repositories\TipoProvaRepository')
            ->needs('App\Models\TipoProva')
            ->give(function () {
                return new TipoProva();
            });

        $this->app->when('App\Services\TipoProvaService')
            ->needs('App\Interfaces\ITipoProvaRepository')
            ->give('App\Repositories\TipoProvaRepository');

        $this->app->when('App\Http\Controllers\Api\TipoProvaController')
            ->needs('App\Interfaces\ITipoProvaService')
            ->give('App\Services\TipoProvaService');
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
