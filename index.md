# PayU Laravel Payment Gateway

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](https://github.com/tzsk/payu/blob/master/LICENSE.md)
[![Code Climate](https://codeclimate.com/github/tzsk/payu/badges/gpa.svg)](https://codeclimate.com/github/tzsk/payu)
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

This is a Package for `PayU India` payment gateway integration with 
`Laravel 5.2 or Higher`. Now payment gateway made simple.

Older Version Support (5.1.\*) is Coming Soon for those who are still using PHP 5.4.\*
## Install

Via Composer

``` bash
$ composer require tzsk/payu
```

## Configure

` config/app.php `
```php
'providers' => [
    ...
    Tzsk\Payu\Provider\PayuServiceProvider::class,
    ...
],

'aliases' => [
    ...
    'Payment' => Tzsk\Payu\Facade\Payment::class,
    ...
],
```

To publish the Configuration file in `config/payu.php` Run:

```bash
php artisan vendor:publish
```

Migrate the `PayuPayment` Database table that comes with the package.

```bash
php artisan migrate
```

## Usage
At the top of your controller file:

```php
...
use Tzsk\Payu\Facade\Payment;
...
```

Inside your controller method you have to pass the Payment data in Payment make function.

**For Example:**

Minimum required fields:

``` php
$data = [
    'txnid' => strtoupper(str_random(8)), # Transaction ID.
    'amount' => rand(100, 999), # Amount to be charged.
    'productinfo' => "Product Information",
    'firstname' => "John", # Payee Name.
    'email' => "john@doe.com", # Payee Email Address.
    'phone' => "9876543210", # Payee Phone Number.
    
    ... # Additional Fields With Data.
    ... # Optional Fields With Data.
];
```

Some additional fields that you can have with the data:

```php
$additional_fields = [
    "lastname", "address1", "address2", 
    "city", "state", "country", "zipcode"
];
```

Some optional fields that you can have with the data:

```php
$optional_fiels = ["udf1", "udf2", "udf3", "udf4", "udf5"];
``` 

Now make the Payment by calling make:

**Note: You have to return the Payment facade**

```php
return Payment::make($data, function($then) {
    $then->redirectTo('payment/status/page'); # Your Status page endpoint.
    # OR...
    $then->redirectAction('PaymentController@status'); # Your Status action.
    # OR...
    $then->redirectRoute('payment_status'); # Your Status Route.
});
    
/**
* So here you will need another route to redirect to after payment is done.
*/

```

Now, in that status route that you have created will receive a Payment 
instance of the Migration table.

**For Example:**

```php
public function status() {
    $payment = Payment::capture(); # Recieve the payment.
    # Returns PayuPayment Instance.
    
    ...
}
```

**PayuPayment API:**

```php
$payment->getData(); # Get the full response from Gateway.
$payment->isCaptured(); # Is the payment captured or some internal failure occured.
$payment->transaction_id; # Your Local Transaction ID.
$payment->payment_id; # PayU Payment ID.
$payment->total_amount; # Get Tototal Amount Deducted.
$payment->bank_reference_number; # Issued Bank Refernce Number.
$payment->bank_code; # Issued Bank Code.
$payment->card_number; # Redacted Card Number. If paid through Card.

...
# And many more like 'status', 'mode' are found in the Database Table.
```

This Package already comes with Polymorphic Relation built in.
Let's asume you want to have it related to an Order. Then you should have
an Order Model. Just use the Trait like.

**Model: `Order.php`**

At the top.

```php
use Tzsk\Payu\Fragment\Payable;
```

Inside the Model Class:

```php
class Order extends Model {

    use Payable;
    
    ...
    
}
```

If you plan to use relationship then you have to modify your status route steps a bit.

**Polymorphic Status Example:**

```php
public function status() {
    $payment = Payment::capture(); # Recieve the payment.
    $payment->fill([
        'payable_id' => $order->id, # Your Order Table Primary Key.
        'payable_type' => 'App\Order', # Your Order Model Namespace.
    ])->save();
    
    ...
}
```

With that you can access the payments for that perticular order: `$order->payments`.

You can perform general Query like : `$order->payments()->where(...)`

**Payment Verify:**

If in case the Amount is deducted from the payee's account but for some reason
it did not redirect back to your Callback URL. In those cases you might like to
Check if a payment is really done or not. In that case you might want to update
your database that the payment was successful.

I recommend that you save your Transaction ID with the ORDER.

```php
$response = Payment::verify("Transaction ID")->simple();
# OR..
$response = Payment::verify(["Array of Transaction IDs"])->simple();

# 'simple()' stands for Simple Response. You can use 'full()' instad.

$response = Payment::verify(...)->full();
```

With the help of the response you can properly update your order status accordingly.

## Change log

Please see [CHANGELOG](https://github.com/tzsk/payu/blob/master/CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/tzsk/payu/blob/master/CONTRIBUTING.md) and [CONDUCT](https://github.com/tzsk/payu/blob/master/CONDUCT.md) for details.

## Security

If you discover any security related issues, please email mailtokmahmed@gmail.com instead of using the issue tracker.

## Credits

- [Kazi Mainuddin Ahmed][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](https://github.com/tzsk/payu/blob/master/LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/tzsk/payu.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/tzsk/payu/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/tzsk/payu.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/tzsk/payu.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/tzsk/payu.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/tzsk/payu
[link-travis]: https://travis-ci.org/tzsk/payu
[link-scrutinizer]: https://scrutinizer-ci.com/g/tzsk/payu/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/tzsk/payu
[link-downloads]: https://packagist.org/packages/tzsk/payu
[link-author]: https://github.com/tzsk
[link-contributors]: https://github.com/tzsk/payu/graphs/contributors
