# Configuration

[[toc]]

When you publish the config file. You will find a `config/payu.php` file in your app which should look lik this,

```php
<?php

use Tzsk\Payu\Gateway\Gateway;
use Tzsk\Payu\Gateway\PayuBiz;
use Tzsk\Payu\Gateway\PayuMoney;
use Tzsk\Payu\Models\PayuTransaction;

return [
    'default' => env('PAYU_DEFAULT_GATEWAY', 'biz'),

    'gateways' => [
        'money' => new PayuMoney([
            'mode' => env('PAYU_MONEY_MODE', Gateway::TEST_MODE),
            'key' => env('PAYU_MONEY_KEY', 'mji6olvE'),
            'salt' => env('PAYU_MONEY_SALT', 'So86G6y4SP'),
            'auth' => env('PAYU_MONEY_AUTH'),
        ]),

        'biz' => new PayuBiz([
            'mode' => env('PAYU_BIZ_MODE', Gateway::TEST_MODE),
            'key' => env('PAYU_BIZ_KEY', 'gtKFFx'),
            'salt' => env('PAYU_BIZ_SALT', 'eCwWELxi'),
        ]),
    ],

    'verify' => [
        PayuTransaction::STATUS_PENDING,
    ],
];
```

## Default Gateway

The first option there specifies which gateway to use by default when you make a payment without specifying a Gateway. The value should match one of the keys in the `gateways` array below it. Otherwise it will throw an exception.

## Gateways

This is an array of key value pairs where you can specify `N` number of gateways if you have them. As you can see that there are only two types of gateway available `PayuBiz` and `PayuMoney`. And the way you make define them is by making an instance with an array of Gateway properties in them.

### Mode

This is the mode in which the gateway should operate on. By default it is set to `Gateway::TEST_MODE` in production you should have it set to `Gateway::LIVE_MODE.

:::tip Modes as Environment Variable.

When you specify the mode in your .env file, the `TEST_MODE` and `LIVE_MODE` values are actually `test` & `live` respectively
:::

### Key

The Merchant Key you get after you create a merchant account with `PayuBiz` or `PayuMoney`.

### Salt

The salt/secret you get from your merchant dashboard.

### Auth

This is only required for `PayuMoney` gateway. You can get it from your merchant dashboard as well.

### Base

You can specify another key `base` in either of the Gateway type. By default it is set to `payu.in`. If you are using this package from any other country this endpoint might change. Refer to your merchant to know what should be your base URL.

:::tip Warning

Only specify the domain. The rest subdomain and verification URL is automatically formatted internally. So don't put anything like `https://secure.payu.nl/_payment` there. Only put `payu.nl` there.
:::

## Verify

Finally you have a verify array which specifies which of the statuses needs verification. When you call for verification in any of the possible ways. You can find more on verification in [Features / Verification](features/verification.html) section.
