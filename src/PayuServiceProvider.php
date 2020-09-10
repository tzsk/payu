<?php

namespace Tzsk\Payu;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Tzsk\Payu\Commands\PublishComponents;
use Tzsk\Payu\Commands\VerifyPendingTransactions;
use Tzsk\Payu\Components\Form;

class PayuServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/payu.php' => config_path('payu.php'),
            ], 'payu-config');

            $this->publishes([
                __DIR__.'/../resources/views' => base_path('resources/views/vendor/payu'),
            ], 'payu-template');

            $migrationFileName = 'create_payu_transactions_table.php';
            $source = __DIR__."/../database/migrations/{$migrationFileName}";

            if (! $this->migrationFileExists($migrationFileName)) {
                $destination = database_path('migrations/'.date('Y_m_d_His', time()).'_'.$migrationFileName);
                $this->publishes([$source => $destination], 'payu-migration');
            }

            $this->commands([
                PublishComponents::class,
                VerifyPendingTransactions::class,
            ]);
        }

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'payu');
        $this->loadRoutesFrom(__DIR__.'/../routes/payu.php');
        $this->callAfterResolving(BladeCompiler::class, function () {
            Blade::component(Form::class, 'payu-form');
        });

        $this->app->bind('payu', function () {
            return new Payu();
        });
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/payu.php', 'payu');
    }

    public static function migrationFileExists(string $migrationFileName): bool
    {
        $len = strlen($migrationFileName);
        foreach (glob(database_path('migrations/*.php')) as $filename) {
            if ((substr($filename, -$len) === $migrationFileName)) {
                return true;
            }
        }

        return false;
    }
}
