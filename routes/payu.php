<?php

use Illuminate\Support\Facades\Route;
use Tzsk\Payu\Controllers\StatusController;

Route::post('vendor-payu/status', StatusController::class)->name('payu::redirect');
