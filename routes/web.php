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
Route::post('/make-payments/{date}', [PaymentController::class,'makePaymentsAmortizations']);

Route::get('/amortizations',[AmortizationController::class, 'getAmortizations']);

