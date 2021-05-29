<?php

namespace Tzsk\Payu\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Spatie\Snapshots\MatchesSnapshots;
use Tzsk\Payu\Concerns\Attributes;
use Tzsk\Payu\Concerns\Customer;
use Tzsk\Payu\Concerns\Transaction;
use Tzsk\Payu\Events\TransactionInitiated;
use Tzsk\Payu\Facades\Payu;
use Tzsk\Payu\Models\PayuTransaction;
use Tzsk\Payu\Tests\Invoice;
use Tzsk\Payu\Tests\TestCase;

class InitiateTransactionTest extends TestCase
{
    use RefreshDatabase;
    use MatchesSnapshots;

    /** @test */
    public function can_create_payu_response()
    {
        Event::fake();
        URL::shouldReceive('route')
            ->andReturn('http://localhost/foo-status');
        URL::shouldReceive('temporarySignedRoute')
            ->andReturn(route('payu::redirect'));

        $payee = Customer::make()
            ->firstName('John Doe')
            ->email('john@example.com');

        $params = Attributes::make()
            ->udf1('client');

        $payment = Transaction::make()
            ->id('unique-transaction')
            ->charge(100)
            ->for('Product')
            ->with($params)
            ->to($payee);

        $response = Payu::initiate($payment)->via('biz')
            ->redirect('http://localhost/transaction/status');

        $this->assertEquals('payu::form', $response->name());

        /** @var PayuTransaction $transaction */
        $transaction = PayuTransaction::query()
            ->locate('unique-transaction');
        $this->assertMatchesSnapshot($transaction->body->toArray());
        $this->assertMatchesSnapshot($transaction->body->params->toArray());
        $this->assertMatchesSnapshot($transaction->body->payee->toArray());

        $this->assertEquals('unique-transaction', Session::get('payuTransactionId'));
        Event::assertDispatched(TransactionInitiated::class, fn ($event) => $event->transaction instanceof PayuTransaction);
    }

    /** @test  */
    public function can_create_payu_money_payments_response()
    {
        Event::fake();
        URL::shouldReceive('route')
            ->andReturn('http://localhost/foo-status');
        URL::shouldReceive('temporarySignedRoute')
            ->andReturn(route('payu::redirect'));

        $payee = Customer::make()
            ->firstName('John Doe Money')
            ->email('john.money@example.com');

        $params = Attributes::make()
            ->udf1('money');

        $payment = Transaction::make()
            ->id('unique-money-transaction')
            ->charge(100)
            ->for('Product')
            ->with($params)
            ->to($payee);

        $response = Payu::initiate($payment)
            ->via('money')
            ->redirect('http://localhost/money/status');

        $this->assertEquals('payu::form', $response->name());

        /** @var PayuTransaction $transaction */
        $transaction = PayuTransaction::query()
            ->locate('unique-money-transaction');
        $this->assertMatchesSnapshot($transaction->body->toArray());
        $this->assertMatchesSnapshot($transaction->body->params->toArray());
        $this->assertMatchesSnapshot($transaction->body->payee->toArray());

        $this->assertEquals('unique-money-transaction', Session::get('payuTransactionId'));
        Event::assertDispatched(TransactionInitiated::class, fn ($event) => $event->transaction instanceof PayuTransaction);
    }

    /** @test  */
    public function can_create_payu_morphed_payments_response()
    {
        Event::fake();
        URL::shouldReceive('route')
            ->andReturn('http://localhost/morphed-status');
        URL::shouldReceive('temporarySignedRoute')
            ->andReturn(route('payu::redirect'));

        $payee = Customer::make()
            ->firstName('John Doe Morphed')
            ->email('john.morphed@example.com');

        $params = Attributes::make()
            ->udf1('morphed');

        $payment = Transaction::make()
            ->id('unique-morphed-transaction')
            ->charge(100)
            ->for('Product')
            ->against(Invoice::fake())
            ->with($params)
            ->to($payee);

        $response = Payu::initiate($payment)->via('biz')
            ->redirect('http://localhost/money/status');

        $this->assertEquals('payu::form', $response->name());

        /** @var PayuTransaction $transaction */
        $transaction = PayuTransaction::query()
            ->locate('unique-morphed-transaction');
        $this->assertMatchesSnapshot($transaction->body->toArray());
        $this->assertMatchesSnapshot($transaction->body->params->toArray());
        $this->assertMatchesSnapshot($transaction->body->payee->toArray());
        $this->assertMatchesSnapshot($transaction->body->model->toArray());
        $this->assertSame($transaction->toArray(), Invoice::fake()->transactions()->first()->toArray());

        $this->assertEquals('unique-morphed-transaction', Session::get('payuTransactionId'));
        Event::assertDispatched(TransactionInitiated::class, fn ($event) => $event->transaction instanceof PayuTransaction);
    }
}
