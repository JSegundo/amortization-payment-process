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

    public function getSingleAmortization($id)
    {
        $amortization = Amortization::where('id',$id)->with('project', 'payments')->get();

    if (!$amortization) {
        return response()->json(['error' => 'Amortization not found'], 404);
    }

    return response()->json($amortization);
    }

 public function getAmortizationsToPay($date){
    $amortizations = Amortization::where('state', '=', 'pending')
        ->whereDate('schedule_date', '<=', $date)
        ->with('project', 'payments')
        ->get()
        ->toArray();

    if (empty($amortizations)) {
        return response()->json(['error' => 'Amortizations not found'], 404);
    }

    return response()->json($amortizations);
}


}
