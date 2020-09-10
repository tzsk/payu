<?php

namespace Tzsk\Payu\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @mixin Model
 */
trait HasTransactions
{
    public function transactions(): MorphMany
    {
        return $this->morphMany(PayuTransaction::class, 'paid_for');
    }
}
