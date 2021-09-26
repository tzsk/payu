<?php

namespace Tzsk\Payu\Actions;

use Illuminate\Support\Facades\Http;
use Tzsk\Payu\Exceptions\InvalidValueException;
use Tzsk\Payu\Gateway\Gateway;
use Tzsk\Payu\Gateway\PayuBiz;
use Tzsk\Payu\Models\PayuTransaction;

class VerifyPayuBiz implements Actionable
{
    use Verifier;

    protected PayuBiz $gateway;
    protected string $transactionId;
    protected array $domainMap = [
        Gateway::TEST_MODE => 'test',
        Gateway::LIVE_MODE => 'info',
    ];

    public function handle(PayuTransaction $transaction): bool
    {
        if (! $transaction->shouldVerify()) {
            return false;
        }
        $this->initialize($transaction);

        $response = Http::asForm()->post($this->url(), $this->payload())->json();
        $data = data_get($response, 'transaction_details.'.$this->transactionId);

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
        $subdomain = data_get($this->domainMap, $this->gateway->mode);
        throw_unless($subdomain, InvalidValueException::fromMessage(__('Invalid mode supplied for PayuBiz'), 'mode'));

        return sprintf('https://%s.payu.in/merchant/postservice?form=2', $subdomain);
    }

    protected function payload(): array
    {
        $command = 'verify_payment';
        $values = array_merge($this->gateway->toArray(), [
            'id' => $this->transactionId,
            'command' => $command,
        ]);
        $sequence = collect(['key', 'command', 'id', 'salt'])
            ->map(fn ($key) => data_get($values, $key))
            ->join('|');

        return [
            'key' => $this->gateway->key,
            'hash' => hash('sha512', $sequence),
            'var1' => $this->transactionId,
            'command' => $command,
        ];
    }
}
