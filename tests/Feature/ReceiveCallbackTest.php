<?php

namespace Tzsk\Payu\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Tzsk\Payu\Facades\Payu;
use Tzsk\Payu\Models\PayuTransaction;
use Tzsk\Payu\Tests\GatewayResponse;
use Tzsk\Payu\Tests\TestCase;

class ReceiveCallbackTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_return_captured_transaction()
    {
        /** @var PayuTransaction $transaction */
        $transaction = factory(PayuTransaction::class)->create();
        Session::put('payuTransactionId', $transaction->transaction_id);

        $this->assertInstanceOf(PayuTransaction::class, $captured = Payu::capture());
        $this->assertSame($transaction->transaction_id, $captured->transaction_id);
    }

    /** @test */
    public function it_can_receive_success_post_back_from_payu()
    {
        /** @var PayuTransaction $transaction */
        $transaction = factory(PayuTransaction::class)->create();
        $body = new GatewayResponse($transaction);

        $url = $this->getUrl($transaction, PayuTransaction::STATUS_SUCCESSFUL);
        $payload = $body->payment('success');

        $this->assertTrue($transaction->pending());
        $this->assertFalse($transaction->successful());

        $response = $this->postJson($url, $payload);
        $response->assertRedirect($transaction->destination);

        $fresh = $transaction->fresh();
        $this->assertFalse($fresh->pending());
        $this->assertTrue($fresh->successful());
    }

    /** @test */
    public function it_can_receive_failure_post_back_from_payu()
    {
        /** @var PayuTransaction $transaction */
        $transaction = factory(PayuTransaction::class)->create();
        $body = new GatewayResponse($transaction);

        $url = $this->getUrl($transaction, PayuTransaction::STATUS_FAILED);
        $payload = $body->payment('failure');

        $this->assertTrue($transaction->pending());
        $this->assertFalse($transaction->failed());

        $response = $this->postJson($url, $payload);
        $response->assertRedirect($transaction->destination);

        $fresh = $transaction->fresh();
        $this->assertFalse($fresh->pending());
        $this->assertTrue($fresh->failed());
    }

    /** @test */
    public function it_can_receive_invalid_post_back_from_payu()
    {
        /** @var PayuTransaction $transaction */
        $transaction = factory(PayuTransaction::class)->create();
        $body = new GatewayResponse($transaction);

        $url = $this->getUrl($transaction, PayuTransaction::STATUS_SUCCESSFUL);
        $payload = $body->payment('failure');

        $this->assertTrue($transaction->pending());
        $this->assertFalse($transaction->invalid());

        $response = $this->postJson($url, array_merge($payload, ['status' => 'success']));
        $response->assertRedirect($transaction->destination);

        $fresh = $transaction->fresh();
        $this->assertFalse($fresh->pending());
        $this->assertTrue($fresh->invalid());
    }

    protected function getUrl(PayuTransaction $transaction, string $status)
    {
        return $path = URL::temporarySignedRoute(
            'payu::redirect',
            now()->addMinutes(30),
            ['transaction' => $transaction->transaction_id, 'urlType' => $status],
        );
    }
}
