<?php

namespace Mchervenkov\Inout;

use Illuminate\Support\ServiceProvider;

class InoutServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__ . '/../config/inout.php' => config_path('inout.php'),
            ], 'inout-config');
//
//            $this->publishes([
//                __DIR__ . '/../database/migrations/' => database_path('migrations'),
//            ], 'sameday-migrations');
//
//            $this->publishes([
//                __DIR__ . '/Models/' => app_path('Models'),
//            ], 'sameday-models');
//
//            $this->publishes([
//                __DIR__ . '/Commands/' => app_path('Console/Commands'),
//            ], 'sameday-commands');
//
//            // Registering package commands.
//            $this->commands([
//                GetSamedayCounties::class,
//                GetSamedayCities::class,
//                GetSamedayLockers::class,
//                GetSamedayApiStatus::class,
//                MapSamedayCities::class,
//            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/inout.php', 'inout');

        // Register the main class to use with the facade
        $this->app->singleton('inout', function () {
            return new Inout();
        });
    }
}
