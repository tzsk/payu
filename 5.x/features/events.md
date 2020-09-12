# Events

[[toc]]

Laravel PayU dispatches number of events that you can listen to, to process your transaction accordingly.

## Transaction Initiated

When a transaction is initiated Laravel PayU dispatches an event `Tzsk\Payu\Events\TransactionInitiated`. You can create a listener for it and access the transaction instance like this,

```php

class TransactionInitiatedListener implements ShouldQueue
{
    public function handle(TransactionInitiated $event)
    {
        $event->transaction; // PayuTransaction Instance
    }
}
```

## Transaction Successful

When a transaction is completed with a status of `SUCCESSFUL`, Laravel PayU dispatches an event `Tzsk\Payu\Events\TransactionSuccessful`. You can create a listener for it and access the transaction instance like this,

```php

class TransactionSuccessfulListener implements ShouldQueue
{
    public function handle(TransactionSuccessful $event)
    {
        $event->transaction; // PayuTransaction Instance
    }
}
```

## Transaction Failed

When a transaction is completed with a status of `FAILED`, Laravel PayU dispatches an event `Tzsk\Payu\Events\TransactionFailed`. You can create a listener for it and access the transaction instance like this,

```php

class TransactionFailedListener implements ShouldQueue
{
    public function handle(TransactionFailed $event)
    {
        $event->transaction; // PayuTransaction Instance
    }
}
```

## Transaction Invalidated

When a transaction is completed with a status of `INVALID`, Laravel PayU dispatches an event `Tzsk\Payu\Events\TransactionInvalidated`. You can create a listener for it and access the transaction instance like this,

```php

class TransactionInvalidatedListener implements ShouldQueue
{
    public function handle(TransactionInvalidated $event)
    {
        $event->transaction; // PayuTransaction Instance
    }
}
```

:::tip Verification Event

When you verify a transaction through whatever means be it Manual, Queue or CRON after verification is complete it also dispatches the events depending on the Status Received from the Payu server. It can be either `Failed` or `Successful`.
:::
