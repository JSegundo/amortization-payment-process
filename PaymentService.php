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
       // ensure date is set, default to the current date if not
        $date = $date ?? Carbon::now();

        // loop through amortizations in chunks to reduce memory usage
            Amortization::where('state', '=', 'pending')
            ->whereDate('schedule_date', '<=', $date)
            ->with('project', 'payments')
            ->chunkById(200, function ($amortizations) use ($date) {

                // initialize arrays to hold batch updates
                $amortizationUpdates = [];
                $paymentUpdates = [];
                $projectUpdates = [];

                foreach ($amortizations as $amortization) {

                    // process each amortization within a database transaction
                    // // using a transaction to make sure all changes happen successfully or not at all
                    DB::transaction(function () use ($amortization, $date, &$amortizationUpdates, &$paymentUpdates) {
                        $project = $amortization->project;

                         //check wallet balance
                        if ($project->wallet_balance >= $amortization->amount) {
                         // collecting new values for amortizations, projects, and payments to update them all at once later
                            $amortizationUpdates[] = [
                                'id' => $amortization->id,
                                'state' => 'paid'
                            ];

                            $newWalletBalance = $project->wallet_balance - $amortization->amount;
                            $projectUpdates[] = [
                                'id' => $project->id,
                                'wallet_balance' => $newWalletBalance
                            ];

                            foreach ($amortization->payments as $payment) {
                                $paymentUpdates[] = [
                                    'id' => $payment->id,
                                    'state' => 'paid'
                                ];
                            }
                        } else {
                            Log::warning('Insufficient funds for amortization', ['amortization_id' => $amortization->id]);

                            if ($date->greaterThan($amortization->schedule_date)) {
                            // collect profile emails from payments
                                 $profileEmails = $amortization->payments->pluck('profile_email')->toArray();
     
                                 // merge them with the promoter's email
                                 $allEmails = array_merge([$project->promoter_email], $profileEmails);
                                 $emailData = ['projectId' => $project->id, 'scheduleDate' => $amortization->schedule_date];
                                 // send the email
                                 // queue emails for delayed sending, this allows the code to continue running without waiting
                                 Mail::to($allEmails)->queue(new AmortizationDelayed($emailData));
                            }

                            // send an email for insufficient funds
                            $insufficientFundsEmails = [$project->promoter_email];
                            $emailDataIns = [
                                'projectId' => $project->id,
                                'walletBalance' => $project->wallet_balance,
                                'requiredAmount' => $amortization->amount,
                            ];
                            Mail::to($insufficientFundsEmails)->queue(new InsufficientFundsEmail($emailDataIns));
                            }
                    });
                }
                // perform all batch updates outside of the loop - it reduces the number of database calls
                Amortization::whereIn('id', array_column($amortizationUpdates, 'id'))->update(['state' => 'paid']);
                Payment::whereIn('id', array_column($paymentUpdates, 'id'))->update(['state' => 'paid']);
                foreach ($projectUpdates as $update) {
                    Project::where('id', $update['id'])->update(['wallet_balance' => $update['wallet_balance']]);
                }

            });
    }
}

// to send the email example
// App\Mail\AmortizationDelayed;

// public function build()
// {
//     return $this->view('emails.amortization_delayed')
//                 ->with([
//                     'projectName' => $this->data['projectName'],
//                     'scheduleDate' => $this->data['scheduleDate'],
//                 ]);
// }


// emails.amortization_delayed.php

// <!DOCTYPE html>
// <html>
// <head>
//     <title>Amortization Delayed</title>
// </head>
// <body>

// <h1>Amortization Payment Delayed</h1>

// <p>Dear Team,</p>

// <p>We are writing to inform you that the amortization payment for the project <strong>{{ $projectName }}</strong> scheduled for <strong>{{ $scheduleDate }}</strong> has been delayed.</p>

// <p>Please take immediate action to resolve this issue.</p>

// <p>Thank you</p>

// </body>
// </html>



