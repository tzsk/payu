# Verification

[[toc]]

It might happen that many time you customer will be redirected to the PayU payment gateway and they successfully paid but somehow due to network issue they did not return to the Capture Page.

This might lead to the transactions stuck in `PENDING` state but in reality they should be successful. Best user experience would be to automatically verify the transaction, rather than the user contacting the back office team and manually verify the transaction.

## Manually Verify

```php
$invoice = Invoice::find(1);

// Get the transactions that are pending.
$transactions = $invoice->transactions()->pending()->get();

// Verify Now
$transactions->each->verify();
```

Even if there are 2 `PENDING` transactions and they are from different Gateways one from `PayuBiz` one from `PayuMoney` it can successfully verify them both.

## Verify in Queue

If you want to verify the transaction in a queue then you just have to dispatch the job Laravel PayU provides out of the box.

```php
use Tzsk\Payu\Jobs\VerifyTransaction;

$transactions->each(fn ($transaction) => VerifyTransaction::dispatch($transaction));
```

Or better yet,

```php
$transactions->each->verifyAsync();
```

:::tip When to Dispatch

Best solution is when the user visits the Order Detail which is still in pending status. You can dispatch the job right then so that it can go ahead and verify on demand.
:::

You can easily check if a Transaction is verified or not by calling `$transaction->verified()` method. This will return boolean depending on the verification status.

**Remember:** A failed transaction can also be Verified. Verified doesn't mean it is successful.

## Verify in CRON

If you think about it, this is actually the practical solution. Cause the customer never returned to your application after payment that's why there is this issue of Transactions Stuck in `PENDING` state. So the best solution is to add it in a scheduled task for every 15 mins.

Laravel PayU has you covered in this area as well. It comes with a verification command out of the box. Just add this line in the `schedule` method of your `console/Kernel.php`.

```php
$schedule->command('payu:verify')->everyFifteenMinutes();
```

:::tip Status List to Verify

You can specify which status of the transaction needs to be verified in the payu config file. `verify` key. By default it is set to `PayuTransaction::STATUS_PENDING`.
:::
