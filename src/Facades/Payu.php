<?php

namespace Tzsk\Payu\Facades;

use Illuminate\Support\Facades\Facade;
use Tzsk\Payu\Concerns\Transaction;
use Tzsk\Payu\Models\PayuTransaction;

/**
 * @see \Tzsk\Payu\Payu
 * @method static \Tzsk\Payu\Payu initiate(Transaction $payment)
 * @method static PayuTransaction capture()
 */
class Payu extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'payu';
    }
}
