<?php

namespace Tzsk\Payu\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Tzsk\Payu\Contracts\HasFormParams;

class Transaction implements HasFormParams
{
    public ?string $transactionId = null;
    public ?float $amount = null;
    public ?string $productInfo = null;
    public ?Customer $payee;
    public ?Attributes $params;
    public ?Model $model = null;

    public static function make(?string $transactionId = null): self
    {
        return (new self())->id($transactionId ?? Str::random(10));
    }

    public function id(string $transactionId): self
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    public function charge(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function for(string $productInfo): self
    {
        $this->productInfo = $productInfo;

        return $this;
    }

    public function to(Customer $payee): self
    {
        $this->payee = $payee;

        return $this;
    }

    public function with(Attributes $params): self
    {
        $this->params = $params;

        return $this;
    }

    public function against(Model $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'txnid' => $this->transactionId,
            'amount' => $this->amount,
            'productinfo' => $this->productInfo,
        ];
    }

    /**
     * @throws ValidationException
     */
    public function validate(): array
    {
        return Validator::make($this->toArray(), [
            'txnid' => 'required|string',
            'amount' => 'required|numeric',
            'productinfo' => 'required|string',
        ])->validate();
    }

    public function fields(): array
    {
        return collect($this->toArray())
            ->merge($this->payee->fields())
            ->merge($this->params->fields())
            ->all();
    }
}
