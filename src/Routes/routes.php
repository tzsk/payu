<?php

/**
 * Make the payment request.
 */
Route::group(['middleware' => 'web', 'namespace' => 'Tzsk\Payu\Controllers'], function () {
    Route::get('tzsk/payment', 'PaymentController@index');
});

/**
 * Get Response from payment.
 */
Route::post('tzsk/payment/{status}', 'Tzsk\Payu\Controllers\PaymentController@payment');