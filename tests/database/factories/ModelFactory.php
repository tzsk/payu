<?php

use Faker\Generator;
use Tzsk\Payu\Checksum;
use Tzsk\Payu\Concerns\Attributes;
use Tzsk\Payu\Concerns\Customer;
use Tzsk\Payu\Concerns\Transaction;
use Tzsk\Payu\Gateway\Factory;
use Tzsk\Payu\Models\PayuTransaction;

/* @var Illuminate\Database\Eloquent\Factory $factory */
$factory->define(PayuTransaction::class, function (Generator $faker) {
    $customer = Customer::make()
        ->firstName($faker->firstName)
        ->email($faker->email);

    $attributes = Attributes::make()
        ->udf1($faker->company);

    $transaction = Transaction::make()
        ->charge($faker->numberBetween(100, 300))
        ->for($faker->firstName)
        ->with($attributes)
        ->to($customer);

    $gateway = Factory::make('biz');

    return [
        'transaction_id' => $transaction->transactionId,
        'gateway' => $gateway,
        'destination' => $faker->url,
        'body' => $transaction,
        'hash' => Checksum::with($gateway->salt())->create($transaction->toArray()),
        'status' => PayuTransaction::STATUS_PENDING,
    ];
});
