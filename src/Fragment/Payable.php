<?php

namespace Tzsk\Payu\Fragment;

use Tzsk\Payu\Model\PayuPayment;

trait Payable
{
    /**
     * Relation for inclusion in a Model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function payments()
    {
        return $this->morphMany(PayuPayment::class, 'payable');
    }
}
