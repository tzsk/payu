<?php
/**
 * Routes for payment.
 */

Route::group(['namespace' => 'Tzsk\Payu\Controllers', 'middleware' => ['web']], function () {
    /**
     * Make the payment request.
     */
    Route::get(
        'tzsk/payment',
        ['as' => 'tzsk.payu.payment', 'uses' => 'PaymentController@index']
    );

    /**
     * Get Response from payment.
     */
    Route::post(
        'tzsk/payment/{status}',
        ['as' => 'tzsk.payu.payment.status', 'uses' => 'PaymentController@payment']
    );
});
