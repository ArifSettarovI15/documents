<?php


namespace App\Jobs;


use App\Mail\SendInvoiceMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class SendMailIfNotPayed5Days implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $subject;
    protected $details;

    /**
     * Create a new job instance.
     *
     * @param $subject
     * @param $details
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to('arif.settarov@mail.ru')->send(new SendInvoiceMail("Выставленный вам счет не был оплачен в течении 5 дней", $this->details));
    }
}
