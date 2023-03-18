<?php

namespace Tzsk\Payu\Actions;

use Illuminate\Support\Facades\Http;
use Tzsk\Payu\Exceptions\InvalidValueException;
use Tzsk\Payu\Gateway\Gateway;
use Tzsk\Payu\Gateway\PayuMoney;
use Tzsk\Payu\Models\PayuTransaction;

class VerifyPayuMoney implements Actionable
{
    use Verifier;

    protected PayuMoney $gateway;

    protected string $transactionId;

    protected array $partMap = [
        Gateway::TEST_MODE => 'sandbox/',
        Gateway::LIVE_MODE => '',
    ];

    public function handle(PayuTransaction $transaction)
    {
        if (! $transaction->shouldVerify()) {
            return false;
        }

        $this->initialize($transaction);

        $response = Http::withHeaders(['Authorization' => $this->gateway->auth()])->post($this->url());
        $data = collect(data_get($response->json(), 'result', []))->first();

        $this->verify($transaction, $data);

        return true;
    }

    protected function initialize(PayuTransaction $transaction)
    {
        $this->gateway = $transaction->gateway;
        $this->transactionId = data_get($transaction, 'transaction_id');
    }

    protected function url(): string
    {
        $part = data_get($this->partMap, $this->gateway->mode);
        throw_unless($part, InvalidValueException::fromMessage(__('Invalid mode supplied for PayuBiz'), 'mode'));

        return sprintf('https://www.payumoney.com/%spayment/op/getPaymentResponse?%s', $part, $this->getQuery());
    }

    protected function getQuery(): string
    {
        return http_build_query([
            'merchantKey' => $this->gateway->key,
            'merchantTransactionIds' => $this->transactionId,
        ]);
    }
}
