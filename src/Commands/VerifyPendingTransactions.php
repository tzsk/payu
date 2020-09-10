<?php

namespace Tzsk\Payu\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Tzsk\Payu\Jobs\VerifyTransaction;
use Tzsk\Payu\Models\PayuTransaction;

class VerifyPendingTransactions extends Command
{
    public $signature = 'payu:verify';

    public $description = 'Verify pending transactions';

    public function handle()
    {
        $transactions = PayuTransaction::verifiable()->get();
        $transactions->each(fn (PayuTransaction $transaction) => dispatch(new VerifyTransaction($transaction)));

        $count = $transactions->count();
        $this->line(
            sprintf('Verification Done for %s %s', $count, Str::plural('Transaction', $count)),
            'fg=green'
        );
    }
}
