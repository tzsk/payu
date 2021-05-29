<?php

namespace Tzsk\Payu\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Tzsk\Payu\Models\PayuTransaction;

class VerifyTransaction implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public PayuTransaction $transaction;

    public function __construct(PayuTransaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function handle()
    {
        $this->transaction->verify();
    }
}
