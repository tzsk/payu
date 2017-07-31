<?php
/**
 * Routes for payment.
 */

Route::group(['namespace' => 'Tzsk\Payu\Controllers'], function() {

    /**
     * Make the payment request.
     */
    Route::get('tzsk/payment', 'PaymentController@index');

    /**
     * Get Response from payment.
     */
    Route::post('tzsk/payment/{status}', 'PaymentController@payment');
});
