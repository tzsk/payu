<?php

namespace Tzsk\Payu\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Tzsk\Payu\Checksum;
use Tzsk\Payu\Models\PayuTransaction;

class GatewayResponse
{
    use WithFaker;

    public PayuTransaction $transaction;

    public array $fields;

    public function __construct(PayuTransaction $transaction)
    {
        $this->transaction = $transaction;
        $this->setUpFaker();
        $this->makeFields();
    }

    public function payment(string $status): array
    {
        $hash = Checksum::with($this->transaction->gateway->salt())
            ->generate(array_merge($this->fields, compact('status')), Checksum::REVERSE);

        return array_merge([
            'mihpayid' => $this->faker->randomNumber(6),
            'status' => $status,
            'hash' => $hash,
        ], $this->fields);
    }

    protected function makeFields()
    {
        $this->fields = array_merge(
            $this->transaction->gateway->fields(),
            $this->transaction->body->fields()
        );
    }
}
