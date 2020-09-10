<?php

namespace Tzsk\Payu\Actions;

use Tzsk\Payu\Models\PayuTransaction;

trait Verifier
{
    public function verify($transaction, $data)
    {
        if (!$data) {
            $transaction->update([
                'status'      => PayuTransaction::STATUS_FAILED,
                'verified_at' => now(),
            ]);

            return;
        }

        $successful = data_get($data, 'status') === 'success';
        $transaction->update([
            'status'      => $successful ? PayuTransaction::STATUS_SUCCESSFUL : PayuTransaction::STATUS_FAILED,
            'verified_at' => now(),
        ]);
    }
}
