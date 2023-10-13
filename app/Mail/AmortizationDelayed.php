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

    public function __construct($amortization)
    {
        $this->amortization = $amortization;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Amortization Delayed')
            ->view('emails.amortization_delayed')  // This is the Blade template used for the email body
            ->with([
                'amortization' => $this->amortization
            ]);}
}
