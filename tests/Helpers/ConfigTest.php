<?php

namespace Tzsk\Payu\Tests\Helpers;

use Tzsk\Payu\Tests\TestCase;
use Tzsk\Payu\Helpers\Config;

class ConfigTest extends TestCase
{
    public function testItWillHaveSomeBasicConfig()
    {
        $config = $this->getConfig();

        $this->assertContains($config->getDriver(), ['session', 'database']);
        $this->assertContains($config->getEnv(), ['test', 'secure']);
        $this->assertTrue(is_array($config->getRequiredFields()));
        $this->assertTrue(is_array($config->getOptionalFields()));
        $this->assertTrue(is_array($config->getAdditionalFields()));
        $this->assertTrue(is_array($config->getRedirect()));
        $this->assertTrue(is_string($config->getEndpoint()));
    }

    public function testItWillHaveTheDefaultConfigWithoutAccount()
    {
        $config = $this->getConfig();
        $default = $this->getDefaultConfig();

        $this->assertEmpty($config->getAccount());
        $this->assertEquals($default['key'], $config->getKey());
        $this->assertEquals($default['salt'], $config->getSalt());
        $this->assertEquals($default['money'], $config->isPayuMoney());
        $this->assertEquals($default['auth'], $config->getAuth());
    }

    public function testItWillHaveSpecificAccountValue()
    {
        $config = $this->getConfig($account = 'payumoney');
        $default = $this->getDefaultConfig($account);

        $this->assertEquals($account, $config->getAccount());
        $this->assertEquals($default['key'], $config->getKey());
        $this->assertEquals($default['salt'], $config->getSalt());
        $this->assertEquals($default['money'], $config->isPayuMoney());
        $this->assertEquals($default['auth'], $config->getAuth());
    }

    /**
     * @param string|null $account
     * @return Config
     */
    protected function getConfig($account = null)
    {
        return new Config($account);
    }

    /**
     * @param string|null $account
     * @return array
     */
    protected function getDefaultConfig($account = null)
    {
        $reference = config('payu');

        return $reference['accounts'][$account ?: $reference['default']];
    }
}
