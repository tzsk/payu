<?php

namespace Tzsk\Payu;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Tzsk\Payu\Components\Form;
use Tzsk\Payu\Concerns\Transaction;
use Tzsk\Payu\Contracts\HasFormParams;
use Tzsk\Payu\Events\TransactionInitiated;
use Tzsk\Payu\Exceptions\InvalidValueException;
use Tzsk\Payu\Gateway\Factory;
use Tzsk\Payu\Gateway\Gateway;
use Tzsk\Payu\Models\PayuTransaction;

class Payu implements HasFormParams
{
    protected ?string $destination = null;

    protected ?Gateway $gateway = null;

    protected ?Transaction $payment = null;

    /**
     * @throws InvalidValueException
     */
    public function via(string $gateway): self
    {
        $this->gateway = Factory::make($gateway);

        return $this;
    }

    public function initiate(Transaction $payment): self
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * @throws InvalidValueException
     */
    public function redirect(string $url): View
    {
        try {
            Validator::make(compact('url'), ['url' => 'required|url'])->validate();
        } catch (ValidationException $e) {
            throw InvalidValueException::fromValidationException($e);
        }

        $this->destination = $url;
        if (! $this->gateway) {
            $this->via($this->defaultGateway());
        }

        Form::inject($payload = $this->prepare());

        event(new TransactionInitiated($payload['transaction']));

        return view('payu::form');
    }

    /**
     * @throws InvalidValueException
     */
    protected function prepare()
    {
        $this->validate();
        $fields = $this->fields();
        $hash = $this->getHash();

        $transaction = PayuTransaction::query()
            ->firstOrNew(['transaction_id' => $this->payment->transactionId]);

        $attr = array_merge($this->morphFields(), [
            'gateway' => $this->gateway,
            'body' => $this->payment,
            'destination' => $this->destination,
            'hash' => $hash,
        ]);
        $transaction->fill($attr)->save();

        Session::put('payuTransactionId', $this->payment->transactionId);

        return [
            'endpoint' => $this->gateway->endpoint(),
            'fields' => array_merge($fields, compact('hash')),
            'transaction' => $transaction,
        ];
    }

    public function capture(): PayuTransaction
    {
        return PayuTransaction::locate(Session::get('payuTransactionId'));
    }

    protected function morphFields()
    {
        if (! $this->payment->model) {
            return [];
        }

        return [
            'paid_for_id' => $this->payment->model->getKey(),
            'paid_for_type' => $this->payment->model->getMorphClass(),
        ];
    }

    protected function defaultGateway()
    {
        return config('payu.default');
    }

    public function toArray(): array
    {
        return [
            'furl' => $this->getSignedRoute('failed'),
            'surl' => $this->getSignedRoute('successful'),
        ];
    }

    public function fields(): array
    {
        return collect($this->toArray())
            ->merge($this->gateway->fields())
            ->merge($this->payment->fields())
            ->all();
    }

    /**
     * @throws InvalidValueException
     */
    public function validate(): array
    {
        $this->gateway->validate();
        $this->payment->payee->validate();
        $this->payment->params->validate();
        $this->payment->validate();

        try {
            return Validator::make($this->toArray(), [
                'surl' => 'required|url',
                'furl' => 'required|url',
            ])->validate();
        } catch (ValidationException $e) {
            throw InvalidValueException::fromValidationException($e);
        }
    }

    public function getSignedRoute(string $urlType): string
    {
        return URL::temporarySignedRoute(
            'payu::redirect',
            now()->addMinutes(30),
            array_merge(compact('urlType'), ['transaction' => $this->payment->transactionId])
        );
    }

    protected function getHash()
    {
        return Checksum::with($this->gateway->salt())
            ->create($this->fields());
    }
}
