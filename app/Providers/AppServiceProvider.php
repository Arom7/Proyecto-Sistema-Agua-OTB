<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// Repositories para el socio
use App\Repositories\Eloquent\SocioRepository;
use App\Repositories\Interfaces\SocioRepositoryInterface;
//Repositories para el usuario
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Interfaces\UserRepositoryInterface;
//Repositories para la propiedad
use App\Repositories\Eloquent\PropiedadRepository;
use App\Repositories\Interfaces\PropiedadInterfaceRepository;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //vincular la interfaz con la implementación
        $this->app->bind(
            SocioRepositoryInterface::class,
            SocioRepository::class
        );
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );
        $this->app->bind(
            PropiedadInterfaceRepository::class,
            PropiedadRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
