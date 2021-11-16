<?php

namespace App\Jobs;

use App\Mail\SendInvoiceMail;
use App\Modules\Invoices\Models\InvoiceModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $subject;
    protected $details;
    protected $to;

    /**
     * Create a new job instance.
     *
     * @param $subject
     * @param $details
     */
    public function __construct($subject,$details, $to='arif.settarov@mail.ru')
    {
        $this->subject = $subject;
        $this->details = $details;
        $this->to = $to;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $file = explode('/',$this->details['file']);


        InvoiceModel::where('invoice_email', $this->to)->where('invoice_sended', 0)->where('invoice_file', last($file))->update(['invoice_sended'=> 1]);

        Mail::to($this->to)->send(new SendInvoiceMail($this->subject, $this->details));

    }
}
