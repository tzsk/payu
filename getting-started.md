# Getting Started

Follow the below steps to get started with the PayuBiz & PayuMoney Payment Gateway Integration with Laravel.

## Installation

Inside your Project Root folder run the following composer command:

```bash
$ composer required tzsk/payu
```

> **Note:** This package has `Auto Discovery` for **Laravel 5.5 and above** you don't have to include the `Service Provider` & `Alias` to the `config/app.php` file.

## Configuration

For Laravel versions below that, you would need to add the following in respective places of `config/app.php` file:

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
]
```

After that is done, run the following command to publish the Configuration file and the Migration file.
Configuration file is mandatory but the Migration is not if you don't want to use `database` driver for the payment gateway.

**For Config File:**

```bash
$ php artisan vendor:publish --tag=payu-config
```

**For Migration File:**

If you wish to use `database` driver as stated in [Configuration Option](/config?id=database-driver-configuration) and [Polymorphic Relationship](/usage?id=polymorphic-relationship) then it is mandatory to publish the Migration file.

```bash
$ php artisan vendor:publish --tag=payu-table
```

> **NOTE:** For interactive publishing you can run `php artisan vendor:publish` and then choose `payu-config` or `payu-table` from the options. If you don't know what that is, run the above specified commands mentioned at the top of this NOTE.