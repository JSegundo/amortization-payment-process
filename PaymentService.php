<?php
namespace App\Services;

use App\Models\Amortization;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Mail\AmortizationDelayed;


class PaymentService
{
    public function payAmortizations(Carbon $date)
    {
        $date = $date ?? Carbon::now();
        $date->addDays(2000);

        // fetch all unpaid amortizations with a schedule date equal to or before the given date.
        $amortizations = Amortization::where('state', '=', 'pending')
            ->whereDate('schedule_date', '<=', $date)
            ->with('project', 'payments')
            ->get();

        echo($amortizations);

        // loop through each amortization.
        foreach ($amortizations as $amortization) {
            //DB::transaction helps avoid inconsistencies in the database
            DB::transaction(function () use ($amortization, $date) {
                $project = $amortization->project;

                // check if the project's wallet balance is sufficient.
                if ($project->wallet_balance >= $amortization->amount) {
                    // pay the amortization
                    $amortization->state = 'paid';
                    $amortization->save();

                    // update project's wallet balance
                    $project->wallet_balance -= $amortization->amount;
                    $project->save();

                    // handle payments associated with this amortization
                    foreach ($amortization->payments as $payment) {
                        $payment->state = 'paid';
                        $payment->save();
                    }
                } else {
                    // check if the amortization is delayed
                  // check if the amortization is delayed
                    if ($date->greaterThan($amortization->schedule_date)) {
                        // collect profile emails from payments
                        $profileEmails = $amortization->payments->pluck('profile_email')->toArray();

                        // merge them with the promoter's email
                        $allEmails = array_merge([$project->promoter_email], $profileEmails);

                        // prepare the data for the email
                        $emailData = [
                            'projectName' => $project->name,
                            'scheduleDate' => $amortization->schedule_date,
                        ];

                        // send the email
                        Mail::to($allEmails)->send(new AmortizationDelayed($emailData));
                    }
                }
            });
        }
    }
}



