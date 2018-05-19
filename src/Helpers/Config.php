<?php

namespace Tzsk\Payu\Helpers;

class Config
{
    /**
     * @var string
     */
    protected $account;

    /**
     * @var string
     */
    protected $default;

    /**
     * @var string
     */
    protected $env;

    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $salt;

    /**
     * @var boolean
     */
    protected $money = false;

    /**
     * @var string
     */
    protected $auth;

    /**
     * @var string
     */
    protected $driver;

    /**
     * @var string
     */
    protected $endpoint;

    /**
     * @var array
     */
    protected $required_fields;

    /**
     * @var array
     */
    protected $optional_fields;

    /**
     * @var array
     */
    protected $additional_fields;

    /**
     * @var array
     */
    protected $redirect;

    /**
     * @var mixed
     */
    protected $config;

    /**
     * @param string $account
     */
    public function __construct($account = null)
    {
        $this->config = config('payu');
        $this->account = $account;

        $this->assign($this->config);
        $this->boot();
    }

    /**
     * @param array $config
     */
    public function assign($config)
    {
        foreach ($config as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    public function boot()
    {
        $account = $this->account ? $this->account : $this->config['default'];

        try {
            $credentials = $this->config['accounts'][$account];
        } catch (\Exception $e) {
            throw new \Exception('Account credentials does not exist.');
        }

        $this->assign($credentials);
    }

    /**
     * @param string $account
     * @return Config
     */
    public function setAccount($account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * @return string
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @return string
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @return string
     */
    public function getEnv()
    {
        return $this->env;
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * @return array
     */
    public function getRequiredFields()
    {
        return $this->required_fields;
    }

    /**
     * @return array
     */
    public function getOptionalFields()
    {
        return $this->optional_fields;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @return array
     */
    public function getAdditionalFields()
    {
        return $this->additional_fields;
    }

    /**
     * @return array
     */
    public function getRedirect()
    {
        return $this->redirect;
    }

    /**
     * @return boolean
     */
    public function isPayuMoney()
    {
        return $this->money;
    }

    /**
     * @return string
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @return string
     */
    public function getAuth()
    {
        return $this->auth;
    }

    /**
     * @return string
     */
    public function getPaymentUrl()
    {
        return 'https://' . $this->prefix() . '.' . $this->endpoint;
    }

    /**
     * @return string
     */
    protected function prefix()
    {
        if ($this->env == 'test' && $this->money) {
            return 'sandboxsecure';
        }

        if ($this->env == 'test') {
            return 'test';
        }

        return 'secure';
    }
}
