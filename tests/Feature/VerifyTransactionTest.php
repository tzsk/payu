<?php

namespace Tzsk\Payu\Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tzsk\Payu\Gateway\Factory;
use Tzsk\Payu\Models\PayuTransaction;
use Tzsk\Payu\Tests\TestCase;

class VerifyTransactionTest extends TestCase
{
    /** @test */
    public function can_verify_biz_transactions()
    {
        /** @var PayuTransaction $transaction */
        $transaction = factory(PayuTransaction::class)->create();

        $payload = [
            'transaction_details' => [$transaction->transaction_id => ['status' => 'success']],
        ];

        Http::fake([
            'test.payu.in/*' => Http::response($payload),
            '*'              => Http::response(''),
        ]);

        $this->assertTrue($transaction->pending());
        $this->assertFalse($transaction->successful());

        $transaction->gateway->verifier()->handle($transaction);

        $fresh = $transaction->fresh();
        $this->assertFalse($fresh->pending());
        $this->assertTrue($fresh->successful());
    }

    /** @test */
    public function can_verify_not_found_biz_transactions()
    {
        /** @var PayuTransaction $transaction */
        $transaction = factory(PayuTransaction::class)->create();

        Http::fake([
            'test.payu.in/*' => Http::response('', 404),
            '*'              => Http::response(''),
        ]);

        $this->assertTrue($transaction->pending());
        $this->assertFalse($transaction->failed());

        $transaction->gateway->verifier()->handle($transaction);

        $fresh = $transaction->fresh();
        $this->assertFalse($fresh->pending());
        $this->assertTrue($fresh->failed());
    }

    /** @test */
    public function can_verify_money_transactions()
    {
        /** @var PayuTransaction $transaction */
        $transaction = factory(PayuTransaction::class)->create([
            'gateway' => Factory::make('money'),
        ]);

        $payload = ['result' => [['status' => 'success']]];
        Http::fake([
            'payumoney.com/*' => Http::response($payload),
            '*'               => Http::response(''),
        ]);

        $this->assertTrue($transaction->pending());
        $this->assertFalse($transaction->successful());

        $transaction->gateway->verifier()->handle($transaction);

        $fresh = $transaction->fresh();
        $this->assertFalse($fresh->pending());
        $this->assertTrue($fresh->successful());
    }

    /** @test */
    public function can_verify_not_found_money_transactions()
    {
        /** @var PayuTransaction $transaction */
        $transaction = factory(PayuTransaction::class)->create([
            'gateway' => Factory::make('money'),
        ]);

        Http::fake([
            'payumoney.com/*' => Http::response([], 404),
            '*'               => Http::response(''),
        ]);

        $this->assertTrue($transaction->pending());
        $this->assertFalse($transaction->failed());

        $transaction->gateway->verifier()->handle($transaction);

        $fresh = $transaction->fresh();
        $this->assertFalse($fresh->pending());
        $this->assertTrue($fresh->failed());
    }
}
