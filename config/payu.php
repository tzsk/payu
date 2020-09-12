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
