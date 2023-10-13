<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Services\PaymentService;

class PaymentController extends Controller
{
    public function makePaymentsAmortizations(PaymentService $paymentService,$date)
    {
        $carbonDate = Carbon::parse($date);
        $paymentService->payAmortizations($carbonDate);
    }

}



