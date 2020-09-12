<?php

namespace Tzsk\Payu\Actions;

use Tzsk\Payu\Events\TransactionFailed;
use Tzsk\Payu\Events\TransactionSuccessful;
use Tzsk\Payu\Models\PayuTransaction;

trait Verifier
{
    public function verify($transaction, $data)
    {
        $successful = data_get($data, 'status') === 'success';
        $transaction->update([
            'status' => $successful ? PayuTransaction::STATUS_SUCCESSFUL : PayuTransaction::STATUS_FAILED,
            'verified_at' => now(),
        ]);

        $event = $successful ? TransactionSuccessful::class : TransactionFailed::class;

        event(new $event($transaction->fresh()));
    }
}
