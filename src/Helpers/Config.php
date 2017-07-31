<?php
namespace Tzsk\Payu\Helpers;

class Config
{
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
     * Config constructor.
     */
    public function __construct()
    {
        foreach (config('payu') as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
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
}
