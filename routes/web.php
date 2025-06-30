<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('payments/dashboard', [PaymentController::class, 'dashboard'])->name('payments.index');
Route::post('payments/upload-csv', [PaymentController::class, 'uploadCsv']);
