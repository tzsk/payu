<?php

namespace Tzsk\Payu\Actions;

use Tzsk\Payu\Models\PayuTransaction;

interface Actionable
{
    public function handle(PayuTransaction $transaction);
}
