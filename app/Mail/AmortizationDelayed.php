<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AmortizationDelayed extends Mailable
{
    use Queueable, SerializesModels;

    public $amortization;

    public function __construct($emailData)
    {
        $this->emailData = $emailData;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
   public function build()
    {
        return $this->view('emails.amortization_delayed')
            ->with([
                'projectName' => $this->emailData['projectName'],
                'scheduleDate' => $this->emailData['scheduleDate'],
            ]);
    }
}
