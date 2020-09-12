<?php

namespace Tzsk\Payu\Actions;

use Illuminate\Http\Request;
use Tzsk\Payu\Events\TransactionFailed;
use Tzsk\Payu\Models\PayuTransaction;

class FailedResponse implements Actionable
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle(PayuTransaction $transaction)
    {
        $transaction->update([
            'response' => $this->request->all(),
            'status' => PayuTransaction::STATUS_FAILED,
        ]);

        event(new TransactionFailed($transaction->fresh()));
    }
}
