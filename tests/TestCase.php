<?php

namespace Tzsk\Payu\Tests;

use CreatePayuTransactionsTable;
use Orchestra\Testbench\TestCase as Orchestra;
use Tzsk\Payu\PayuServiceProvider;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        $this->withFactories(__DIR__.'/database/factories');
    }

    protected function getPackageProviders($app)
    {
        return [
            PayuServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
//        $app['config']->set('payu.gateways, ');

        include_once __DIR__ . '/../database/migrations/create_payu_transactions_table.php';
        (new CreatePayuTransactionsTable())->up();
    }
}
