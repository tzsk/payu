# Making Payment

Below are the steps to use the package in your project.

## Step - 1: Register Routes

We need 2 Routes for accepting payment one for making the payment request and another is for capturing the payment response. In your `web` routes file create 2 Endpoints like below. 
You can have whatever url you like. Below code is just an example:

```php
# Call Route
Route::get('payment', ['as' => 'payment', 'uses' => 'PaymentController@payment']);

# Status Route
Route::get('payment/status', ['as' => 'payment.status', 'uses' => 'PaymentController@status']);
```

> **NOTE:** Make sure both of the above routes are inside `web` middleware. Because this package uses Session. In laravel Session is not available outside `web` middleware. So make sure to run: `php artisan route:list` and see if `web` middleware is applied to both of the routes.

## Step - 2: Make Payment Request

Now, in your controller file, in this case `PaymentController` import the Facade class path at the top. 

Like this:

```php
...
use Tzsk\Payu\Facade\Payment;
...
```

Once that is done, you can make a payment call in your `Call Route` controller method, in this case `payment`.

This is an Example, use your own parameters:

```php
$attributes = [
    'txnid' => strtoupper(str_random(8)), # Transaction ID.
    'amount' => rand(100, 999), # Amount to be charged.
    'productinfo' => "Product Information",
    'firstname' => "John", # Payee Name.
    'email' => "john@doe.com", # Payee Email Address.
    'phone' => "9876543210", # Payee Phone Number.
];

return Payment::make($attributes, function ($then) {
    $then->redirectTo('payment/status');
    # OR...
    $then->redirectRoute('payment.status');
    # OR...
    $then->redirectAction('PaymentController@status');
});
```

> **NOTE:** There are more `parameters` available for making the payment. See the [full list of parameters](/parameters).

Above, the `redirect` methods are just like `redirect()->to()`, `redirect()->route()`, `redirect()->action()`. You can use your own parameters with the routes if you like. See the 
<a href="https://laravel.com/api/5.6/Illuminate/Contracts/Routing/ResponseFactory.html#method_redirectTo" target="_blank">documentation</a> of these Laravel methods to find out.

> **NOTE:** You have to `return` the `Payment` instance. Otherwise it won't take you to the payment gateway.


## Step - 3: Capture Payment

After `Step - 2` you will be redirected to the payment gateway. After you make the payment you will be redirected to your status page by the Package.

Example:

```php
$payment = Payment::capture();

// Get the payment status.
$payment->isCaptured() # Returns boolean - true / false
```

That's it. It is that simple. Now `$payment` is the instance of `PayuPayment` model. So you will have so many functionalities there. [Click Here](/response-api) to see all the possible functionalities.


## Polymorphic Relationship

This package has built in polymorphic relationship with whatever model can accept payment. Let's take an example. You have an `Order` model where users pay for the items they purchase and also you have a `Subscription` model where user has some kind of subscriptions with them.

> **NOTE:** If you want to use this functionality you have to use `database` driver in `config/payu.php` as stated in [Configuration Options](/config?id=database-driver-configuration)

Now, both of them uses payments. For these kinds of payments you can optionally have polymorphic setup.

**Model Setup:**

```php
use Tzsk\Payu\Fragment\Payable;

class Order extends Model
{
    use Payable;
    ...
}

class Subscription extends Model
{
    use Payable;
    ...
}
```

**During Payment Request:**

Now, if you have an order and a subscription instance `$order` and `$subscription` you can make payment like this:

```php
# For orders
return Payment::with($order)->make(...); # $order is an Order instance.

# For subscriptions
return Payment::with($subscription)->make(...); # $subscription is an Subscription instance.
```

Also, you can get collection of all of it's payments like this:

```php
# For orders
$order->payments;

# For subscriptions
$subscription->payments;
```

You can even query the `payu_payments` table via this relationship like this:

```php
# For orders
$order->payments()->where('status', 'Completed')->get();

# For subscriptions
$subscription->payments()->where('status', 'Completed')->get();
```


## Multi Account Payment

As you have seen in the [Configuration Options](/config) section, you can use multiple account setup in your config file.
By default the package will use `default` account mentioned in the config if you don't explicitly mention the account.

Let's say you want your orders to use `payubiz` gateway, which is default so you don't have to do anything, but you want your subscriptions to use `payumoney` account. Then you have to do this:

**During Payment Request:**

```php
return Payment::via('payumoney')->make(...); // Parameter: ACCOUNT_NAME.
# Or...
return Payment::via('payumoney')->with($subscription)->make(...);
```

**During Payment Capture:**

```php
$payment = Payment::via('payumoney')->capture();
```

It's that easy to switch between multiple accounts. You can also have multiple accounts of same kind. For example:
`payubiz_order`, `payubiz_subscription` in the accounts array and you will have different Key & Salt for them.

Then you just have to put that inside of the `via()` method.