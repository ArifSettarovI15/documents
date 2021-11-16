<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $subject_period;
    protected $details;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $details)
    {
        $this->subject = $subject;
        $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->details['file']) {
            $this->attach($this->details['file']);
        }
        return $this
            ->subject('TigerWeb - Счет на оплату ')
            ->view('invoice_mail');
    }
}
