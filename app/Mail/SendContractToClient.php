<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendContractToClient extends Mailable
{
    use Queueable, SerializesModels;


    protected $details;

    /**
     * Create a new message instance.
     *
     * @param $subject
     * @param $details
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
    public function build(): SendContractToClient
    {
        return $this
            ->attach($this->details['contract_file'], ['filename'=>'Договор'])
            ->view('contract_mail');
    }
}
