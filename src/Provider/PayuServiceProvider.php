<?php

namespace Tzsk\Payu\Provider;

use Tzsk\Payu\PayuGateway;
use Illuminate\Support\ServiceProvider;

class PayuServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishFiles();

        $this->loadItems();

        /**
         * Register singleton.
         */
        $this->app->singleton('tzsk-payu', function ($app) {
            return new PayuGateway();
        });
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        /**
         * Merge Configurations.
         */
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/tzsk-payu.php',
            'payu'
        );
    }

    /**
     * Publish Config file and Migration File.
     */
    protected function publishFiles()
    {
        /**
         * Configurations that needs to be done by user.
         */
        $this->publishes([
            __DIR__ . '/../Config/payu.php' => config_path('payu.php'),
        ], 'payu-config');

        /**
         * Migration file for the payments.
         */
        $this->publishes([
            __DIR__ . '/../Migration/' => database_path('migrations')
        ], 'payu-table');
    }

    /**
     * Load Routes and Views.
     */
    protected function loadItems()
    {
        /**
         * Load routes for payment.
         */
        if (! $this->app->routesAreCached()) {
            require __DIR__ . '/../Routes/routes.php';
        }

        /**
         * Load the Views.
         */
        $this->loadViewsFrom(__DIR__ . '/../Views', 'tzsk');
    }
}
