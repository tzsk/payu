<?php

namespace Tzsk\Payu\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * Class Payment
 *
 * @see \Tzsk\Payu\PayuGateway
 */
class Payment extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return 'tzsk-payu';
    }
}
