<?php

namespace Tzsk\Payu\Gateway;

use Illuminate\Validation\ValidationException;
use Throwable;

class Factory
{
    protected function gateways(): array
    {
        return config('payu.gateways', []);
    }

    /**
     * @param string $key
     * @return Gateway
     * @throws Throwable
     */
    public static function make(string $key): Gateway
    {
        $factory = new self();
        $gateway = data_get($factory->gateways(), $key);

        throw_unless($gateway, ValidationException::withMessages([$key => __('Gateway does not exist')]));
        throw_unless($gateway instanceof Gateway, ValidationException::withMessages([$key => 'Invalid gateway']));

        return $gateway;
    }
}
