<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Eloquent\SocioRepository;
use App\Repositories\Interfaces\SocioRepositoryInterface;

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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
