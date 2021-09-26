# Payment

[[toc]]

## Initiate

You can easily initiate payments with the new Intuitive, Fluent, Concern Based syntax. Withing one of your controller method just initiate the Payu.

```php
use Tzsk\Payu\Concerns\Attributes;
use Tzsk\Payu\Concerns\Customer;
use Tzsk\Payu\Concerns\Transaction;
use Tzsk\Payu\Facades\Payu;

$customer = Customer::make()
    ->firstName('John Doe')
    ->email('john@example.com');

// This is entirely optional custom attributes
$attributes = Attributes::make()
    ->udf1('anything');

$transaction = Transaction::make()
    ->charge(100)
    ->for('Product')
    ->with($attributes) // Only when using any custom attributes
    ->to($customer);

return Payu::initiate($transaction)->redirect(route('status'));
```

:::tip Don't Forget

Don't forget to return this redirect from the controller method and also make sure you define the redirect route where you want the user to get redirected once the payment is complete. 
:::

## Capture

Now in the `status` route handler just capture the payu payment.

```php
$transaction = Payu::capture();
```

The above statement returns `Tzsk\Pay\Models\PayuTransaction` model instance. You can do quite a few things with it.

### Getting Status

```php
use Tzsk\Pay\Models\PayuTransaction;

$transaction->status; // Enum
// Enum ->
PayuTransaction::STATUS_PENDING;
PayuTransaction::STATUS_SUCCESSFUL;
PayuTransaction::STATUS_FAILED;
PayuTransaction::STATUS_INVALID;
```

Or you can just use the helper methods provided out of the box.

```php
$transaction->pending(); // Boolean
$transaction->successful(); // Boolean
$transaction->failed(); // Boolean
$transaction->invalid(); // Boolean
```

### Getting Attributes

If you want to get any payload that is sent by payu you can just use the helper method `response(string $key)` to get it.

```php
$transaction->response('mihpayid'); // Payu Payment ID
$transaction->response('bank_ref_num'); // Bank Reference Number

$transaction->response; // Get all attributes
```
