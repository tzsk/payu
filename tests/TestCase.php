<?php

namespace Tzsk\Payu\Tests;

use Tzsk\Payu\Provider\PayuServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function resolveApplicationConfiguration($app)
    {
        parent::resolveApplicationConfiguration($app);

        $app['config']['payu'] = require __DIR__ . '/../src/Config/payu.php';
    }

    protected function getPackageProviders($app)
    {
        return [PayuServiceProvider::class];
    }
}
