<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
   return Inertia::render('Home');
});
Route::get('payments/dashboard', [PaymentController::class, 'dashboard'])->name('payments.index');
Route::post('payments/upload-csv', [PaymentController::class, 'uploadCsv']);
