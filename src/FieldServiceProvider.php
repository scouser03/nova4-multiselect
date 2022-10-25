<?php

namespace Scouser03\Nova4Multiselect;

use Illuminate\Support\Facades\Route as FacadesRoute;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class FieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Nova::serving(function (ServingNova $event) {
            Nova::script('nova4-multiselect', __DIR__.'/../dist/js/field.js');
            Nova::style('nova4-multiselect', __DIR__.'/../dist/css/field.css');
        });

        $this->app->booted(function () {
            $this->routes();
        });
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

    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        FacadesRoute::middleware(['nova', 'api'])
            ->prefix('nova-vendor/scouser03/nova4-multiselect')
            ->group(__DIR__.'/../routes/api.php');
    }
}
