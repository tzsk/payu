<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Required Fields.
    |--------------------------------------------------------------------------
    |
    | These are the fields that are required for making a payment.
    |
    */
    'required_fields' => ['txnid', 'amount', 'productinfo', 'firstname', 'email', 'phone'],

    /*
    |--------------------------------------------------------------------------
    | Optional / Additional Fields.
    |--------------------------------------------------------------------------
    |
    | These are the fields that are optional for making a payment.
    |
    */
    'optional_fields' => array_map(function ($i) {
        return "udf{$i}";
    }, range(1, 10)),

    'additional_fields' => ['lastname', 'address1', 'address2', 'city', 'state', 'country', 'zipcode'],

    /*
    |--------------------------------------------------------------------------
    | Payu Endpoint.
    |--------------------------------------------------------------------------
    |
    | Payment endpoint for Payu.
    |
    */
    'endpoint' => 'payu.in/_payment',

    /*
    |--------------------------------------------------------------------------
    | Redirect Success / Failure URL.
    |--------------------------------------------------------------------------
    |
    | Redirect after payment is complete with respect to Success or Failure.
    |
    */
    'redirect' => [
        'surl' => 'tzsk/payment/success',
        'furl' => 'tzsk/payment/failed',
    ]
];
