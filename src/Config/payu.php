<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Payment Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the environment of the payment gateway.
    | Possible options:
    | "test" For testing and development.
    | "secure" For live payment.
    |
    */

    'env' => 'test',

    /*
    |--------------------------------------------------------------------------
    | Merchant Key
    |--------------------------------------------------------------------------
    |
    | This is the merchant key to be used for payment.
    |
    */
    'key' => 'gtKFFx',

    /*
    |--------------------------------------------------------------------------
    | Merchant Salt
    |--------------------------------------------------------------------------
    |
    | This is the merchant salt to be used for payment.
    |
    */
    'salt' => 'eCwWELxi',

    /*
    |--------------------------------------------------------------------------
    | Payment Store Driver
    |--------------------------------------------------------------------------
    |
    | This is the config for storing the payment info. I recommend to use
    | database driver for storing then use it for your own use.
    | Options : "database", "session".
    | Note: If you use session driver make sure you are using secure = true
    | in config/session.php
    |
    */
    'driver' => 'database',

    /*
    |--------------------------------------------------------------------------
    | Payu Payment Table
    |--------------------------------------------------------------------------
    |
    | This is table that will be used for storing the payment information.
    | Run: php artisan vendor:publish to get the table in the migrations
    | directory. If you did change the table name then specify here.
    |
    */
    'table' => 'payu_payments',

];
