<?php

namespace Tzsk\Payu\Actions;

use Illuminate\Http\Request;
use Tzsk\Payu\Checksum;
use Tzsk\Payu\Events\TransactionInvalidated;
use Tzsk\Payu\Events\TransactionSuccessful;
use Tzsk\Payu\Models\PayuTransaction;

class SuccessResponse implements Actionable
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle(PayuTransaction $transaction)
    {
        $valid = Checksum::with($transaction->gateway->salt())
            ->match($this->request->all(), $this->request->input('hash'));

        $transaction->update([
            'response' => $this->request->all(),
            'status' => $valid ? PayuTransaction::STATUS_SUCCESSFUL : PayuTransaction::STATUS_INVALID,
        ]);

        $fresh = $transaction->fresh();
        $dispatch = $valid ? new TransactionSuccessful($fresh) : new TransactionInvalidated($fresh);

        event($dispatch);
    }
}
