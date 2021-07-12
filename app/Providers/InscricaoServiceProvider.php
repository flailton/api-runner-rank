<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class InscricaoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->when('App\Http\Controllers\Api\InscricaoController')
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
