<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Amortization;

class AmortizationController extends Controller
{
    public function getAmortizations()
    {
        $amortizations = Amortization::select(['schedule_date', 'state', 'amount', 'project_id'])
                                     ->get()
                                     ->toArray();
        return response()->json($amortizations);
    }
}
