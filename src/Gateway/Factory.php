<?php

namespace Tzsk\Payu\Gateway;

use Tzsk\Payu\Exceptions\InvalidValueException;

class Factory
{
    protected function gateways(): array
    {
        return config('payu.gateways', []);
    }

    /**
     * @param string $key
     * @return Gateway
     * @throws InvalidValueException
     */
    public static function make(string $key): Gateway
    {
        $factory = new self();
        $gateway = data_get($factory->gateways(), $key);

        throw_unless($gateway, InvalidValueException::fromMessage(__(sprintf('Gateway [%s] does not exist', $key)), $key));
        throw_unless($gateway instanceof Gateway, InvalidValueException::fromMessage(__(sprintf('Invalid gateway [%s]', $key)), $key));

        return $gateway;
    }
}
