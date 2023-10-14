<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AmortizationController;
use App\Http\Controllers\PaymentController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');

});
Route::get('/make-payments', [PaymentController::class,'makePaymentsAmortizations']);

Route::get('/amortizations',[AmortizationController::class, 'getAmortizations']);

Route::get('/amortizations-to-pay/{date}',[AmortizationController::class, 'getAmortizationsToPay']);

Route::get('/amortizations/{id}',[AmortizationController::class, 'getSingleAmortization']);

