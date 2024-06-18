<?php

namespace Mchervenkov\Inout;

use Illuminate\Support\ServiceProvider;
use Mchervenkov\Inout\Commands\MapInoutCities;
use Mchervenkov\Inout\Commands\SyncCities;
use Mchervenkov\Inout\Commands\SyncCompanyCouriers;
use Mchervenkov\Inout\Commands\SyncCountries;
use Mchervenkov\Inout\Commands\SyncCourierOffices;
use Mchervenkov\Inout\Commands\SyncRomaniaCounties;

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

            $this->publishes([
                __DIR__ . '/../database/migrations/' => database_path('migrations'),
            ], 'inout-migrations');

            $this->publishes([
                __DIR__ . '/Models/' => app_path('Models'),
            ], 'inout-models');

            $this->publishes([
                __DIR__ . '/Commands/' => app_path('Console/Commands'),
            ], 'inout-commands');

            // Registering package commands.
            $this->commands([
                SyncCities::class,
                SyncCompanyCouriers::class,
                SyncCountries::class,
                SyncCourierOffices::class,
                SyncRomaniaCounties::class,
                MapInoutCities::class,
            ]);
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
