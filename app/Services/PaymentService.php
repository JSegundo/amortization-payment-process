<?php
namespace App\Services;

use App\Models\Amortization;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Mail\AmortizationDelayed;
use Illuminate\Support\Facades\Log;


class PaymentService
{

     public function payAmortizations(Carbon $date)
    {
       // ensure date is set
        $date = $date ?? Carbon::now();

        // loop through amortizations in chunks to reduce memory usage
            Amortization::where('state', '=', 'pending')
            ->whereDate('schedule_date', '<=', $date)
            ->with('project', 'payments')
            ->chunkById(200, function ($amortizations) use ($date) {

                // Initialize arrays to hold batch updates
                $amortizationUpdates = [];
                $paymentUpdates = [];
                $projectUpdates = [];

                foreach ($amortizations as $amortization) {

                    // Process each amortization within a database transaction
                    DB::transaction(function () use ($amortization, $date, &$amortizationUpdates, &$paymentUpdates) {
                        $project = $amortization->project;

                        if ($project->wallet_balance >= $amortization->amount) {
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
                            // Mail::to($allEmails)->queue(new AmortizationDelayed($emailData));
                            }

                            // send an email for insufficient funds
                            $insufficientFundsEmails = [$project->promoter_email];
                            $emailDataIns = [
                                'projectId' => $project->id,
                                'walletBalance' => $project->wallet_balance,
                                'requiredAmount' => $amortization->amount,
                            ];
                            // Mail::to($insufficientFundsEmails)->queue(new InsufficientFundsEmail($emailDataIns));
                            }
                    });
                }
                // perform batch updates outside of the loop
                Amortization::whereIn('id', array_column($amortizationUpdates, 'id'))->update(['state' => 'paid']);
                Payment::whereIn('id', array_column($paymentUpdates, 'id'))->update(['state' => 'paid']);
                foreach ($projectUpdates as $update) {
                    Project::where('id', $update['id'])->update(['wallet_balance' => $update['wallet_balance']]);
                }

            });
    }
}


