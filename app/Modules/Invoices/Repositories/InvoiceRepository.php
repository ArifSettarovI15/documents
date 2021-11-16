<?php


namespace App\Modules\Invoices\Repositories;


use App\Jobs\SendEmail;
use App\Jobs\SendMailIfNotPayed5Days;
use App\Mail\SendMailToCheckPayedForAdmin;

use App\Modules\Clients\Models\ClientsModel;
use App\Modules\Companies\Models\CompaniesModel;
use App\Modules\Contracts\Models\ContractsModel;
use App\Modules\Contracts\Models\ContractsServicesModel;
use App\Modules\Invoices\Models\InvoiceModel;
use App\Modules\Plans\Models\PlansModel;
use App\Modules\Services\Models\ServiceInvoiceModel;
use App\Modules\Services\Models\ServicesModel;
use App\Repositories\BaseRepository;
use App\Repositories\Functions\Strings;
use Carbon\Carbon;
use DateTime;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Mail;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;


class InvoiceRepository extends BaseRepository
{
    /**
     * @var $info ContractsModel
     */
    public $info, $services, $rows_count;

    public function __construct()
    {
        $this->model = new InvoiceModel();
    }

    /**
     * @return mixed
     */
    public function get_contracts()
    {
        return ContractsModel::where('contract_status', 1)->get();
    }

    /**
     * @return LengthAwarePaginator
     */
    public function get_not_sent_invoices_to_view() : LengthAwarePaginator
    {

        return InvoiceModel::where('invoice_sended','!=', 2)
                            ->where('invoice_payed', '!=',1)
                            ->orderBy('invoice_id', 'DESC')->paginate(15);
    }

    /**
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function get_invoices_history_to_view($filters = []) : LengthAwarePaginator
    {
        $invoices =  InvoiceModel::query();
        if ($filters)
        {
            foreach ($filters as $field_name => $filter)
            {
                if ($field_name != 'page') {
                    if ($field_name == 'invoice_date') {
                        $filter['value'] = date('Y-m-d', strtotime($filter['value']));
                    }
                    if ($filter['type'] == 'search') {
                        $invoices->where($field_name, 'LIKE', '%' . $filter['value'] . '%');
                    } elseif ($filter['type'] == 'filter') {
                        $invoices->where($field_name, '=', $filter['value']);
                    } elseif ($filter['type'] == 'less') {
                        $invoices->where($field_name, '<=', $filter['value']);
                    } elseif ($filter['type'] == 'more') {
                        $invoices->where($field_name, '>=', $filter['value']);
                    }
                }

            }
        }
        else{
            $invoices->where('invoice_sended', '!=', 0);
        }

        return $invoices->orderBy('invoice_id', 'DESC')->paginate(15);
    }
    /**
     * @var int $client_id
     * @return LengthAwarePaginator
     */
    public function get_client_invoices_history_to_view(int $client_id) : LengthAwarePaginator
    {
        return InvoiceModel::where('invoice_client_id', '=', $client_id)->orderBy('invoice_id', 'DESC')->paginate(15);
    }

    /**
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function get_invoices_not_payed_to_view($filters = []) : LengthAwarePaginator
    {
        if ($filters)
        {
            echo($filters);
        }
        return $this->model->where('invoice_sended', '=', 1)
            ->where('invoice_payed', '=', 0)
            ->orderBy('invoice_id', 'DESC')->paginate(15);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function get_invoice(int $id){
        $invoice = $this->model->find($id)->firstOrFail();
        $invoice->invoice_file_url = Storage::disk('public')->url('invoices/'.$invoice->invoice_file);
        return $invoice;

    }

    /**
     * @param int $id
     * @return mixed
     */
    public function get_invoice_file(int $id){
        $invoice = $this->model->find($id)->firstOrFail();
        return Storage::disk('public')->path('invoices/'.$invoice->invoice_file);
    }

    /**
     * @param int $contract_id
     */
    public function get_dependencies(int $contract_id): void
    {
        $info = ContractsModel::where('contracts.contract_id','=', $contract_id)
                                        ->join('clients', 'clients.client_id','=','contracts.contract_client')
                                        ->join('companies', 'companies.company_id','=','contracts.contract_supplier')
                                        ->first();

        if (!strpos($info->client_phone, '+')) {
            $info->client_phone = '+' . $info->client_phone;
        }
        if (!strpos($info->company_phone, '+')) {
            $info->company_phone = '+' . $info->company_phone;
        }
        if ($info->client_site and $info->client_site != '') {
            $info->client_site = 'на сайте: ' . $info->client_site;
        } else {
            $info->client_site = '';
        }

        $this->info = $info;
        $check_invoices = InvoiceModel::where('invoice_contract_id','=', $contract_id)->first();
        if ($check_invoices) {
            $this->services = ContractsServicesModel::where('cs_contract_id', $contract_id)
                ->where('cs_service_period', '!=', 'once')->get();
        }
        else {
            $this->services = ContractsServicesModel::where('cs_contract_id', $contract_id)->get();
        }
        $this->rows_count = count($this->services);

    }

    /**
     * @param $contract_id
     * @return string
     */
    public function create_invoice_by_contract($contract_id): string
    {
        $this->get_dependencies($contract_id);
        $info = $this->info;
        $rows_count = $this->rows_count;
        $services = $this->services;

        $invoice_number2 = $this->get_invoice_number();
        $date = Carbon::now()->day;
        if ($date < 25)
        {
            $service_month = Carbon::now()->month;
            $service_year = Carbon::now()->year;
        }
        else
        {
            $service_month = Carbon::now()->addMonth()->month;
            $service_year = Carbon::now()->addMonth()->year;
        }
        $filename = $this->create_invoice($info, $invoice_number2, $rows_count, $services, $service_month, $service_year);
        $invoice_id = $this->save_invoice_in_db($invoice_number2, $info->client_id, $contract_id, $filename, Carbon::today()->toDateString(),'none', $sended = 3, $send_active=0);

        foreach ($this->services as $service)
        {
            (new ServiceInvoiceModel)->create([
                'si_service_id'=>$service->cs_service_id,
                'si_invoice_id'=>$invoice_id,
                'si_client_id'=>$info->client_id
            ])->save();
        }
        return Storage::disk('public')->url('invoices/'.$filename);
    }

    /**
     * @return int
     */
    public function create_invoices(): int
    {
        $plans = PlansModel::where('plan_status', 1)->where('plan_success_this_month', 0)->get();
        Log::notice('Plans count: '.count($plans));
        if (!count($plans))
        {
            Log::info('Has no plans to create invoices [date: '.Carbon::now()->toDateString().']');
            return 1;
        }

        foreach ($plans as $plan) {
            if (Carbon::today()->addDays(2)->day != $plan->plan_day)
            {
                Log::info('Day today: '.Carbon::today()->addDays(2)->day);
                Log::info('Plan '.$plan->plan_id.' is not available today [date: '.date('d.m.Y').']');
                continue;
            }

            $this->get_dependencies($plan->plan_contract);

            $info = $this->info;
            $rows_count = $this->rows_count;
            $services = $this->services;

            $invoice_number2 = $this->get_invoice_number();

            if (Carbon::today()->month != Carbon::today()->addDays(2)->month) {
                $service_month = Carbon::today()->addDays(2)->month;
            }
            else {
                $service_month = Carbon::today()->month;
            }

            if (Carbon::today()->day > 25) {
                $service_month = Carbon::today()->addMonth()->month;
            }

            if (Carbon::today()->year != Carbon::today()->addDays(2)->year) {
                $service_year = Carbon::today()->addDays(2)->year;
            }

            else {
                $service_year = Carbon::today()->year;
            }

            if (Carbon::today()->day > 25) {
                $service_year = Carbon::today()->addMonth()->year;
            }

            $filename = $this->create_invoice($info, $invoice_number2, $rows_count, $services, $service_month, $service_year, $plan);


            if(Carbon::today()->dayOfWeek == 4) {
                $send_date = Carbon::today()->addDays(4);
            }
            else if(Carbon::today()->dayOfWeek == 5) {
                $send_date = Carbon::today()->addDays(3);
            }
            else {
                $send_date = Carbon::today()->addDays(2);
            }
            /**
             * @uses InvoiceModel
             */
            $invoice_id = $this->save_invoice_in_db($invoice_number2, $info->client_id, $plan->plan_contract, $filename, $send_date->toDateString(),$info->client_email);
            foreach ($this->services as $service)
            {
                (new ServiceInvoiceModel)->create([
                    'si_service_id'=>$service->service_id,
                    'si_invoice_id'=>$invoice_id,
                    'si_client_id'=>$info->client_id
                ])->save();
            }
            $plan->update(['plan_success_this_month' => 1]);
        }

        return 1;
    }
    public function save_invoice_in_db($invoice_number, $client_id, $contract_id, $filename, $send_date, $client_email='none', $sended = 0, $send_active=1): int
    {
       $invoice =  InvoiceModel::create(
            [
                'invoice_number' => str_replace('-','',$invoice_number),
                'invoice_name' => 'Счет №'.$invoice_number.' от '.date('d.m.Y'),
                'invoice_client_id' => $client_id,
                'invoice_contract_id' => $contract_id,
                'invoice_email' => $client_email ?? '',
                'invoice_file'  => $filename,
                'invoice_date'  => $send_date,
                'invoice_sended' => $sended,
                'invoice_send_active'=>$send_active,
            ]

        );
        return $invoice->invoice_id;
    }

    /**
     * @return string
     */
    public function get_invoice_number(): string
    {
        $invoice_number = InvoiceModel::orderBy('invoice_number', 'DESC')->pluck('invoice_number')->first();
        if (!$invoice_number) {
            $invoice_number = 819100;
        }
        if ($invoice_number < 1000000) {
            $invoice_number = '0' . ++$invoice_number;
        }
        else {
            $invoice_number = (string)++$invoice_number;
        }

        $invoice_number2 = chunk_split($invoice_number, 2, '-');
        if (mb_substr($invoice_number2, -1) == '-') {
            $invoice_number2 = mb_substr($invoice_number2, 0, -1);
        }
        if(mb_substr($invoice_number2, -2, -1) == '-')
        {
            $invoice_number2 = mb_substr($invoice_number2, 0, -2).mb_substr($invoice_number2, -1);
        }
        return $invoice_number2;
    }

    public function create_invoice($info, $invoice_number,$rows_count, $services, $service_month, $service_year, $plan=null, $invoice_date=null):string
    {
        try {
            Settings::setTempDir(Storage::disk('temp')->path('/'));
            $template = new TemplateProcessor(Storage::disk('public')->path('template_invoice.docx'));
        }catch (\Exception $exception){
            if (isset($plan)) {
                Log::critical('Invoice file to plan:' . $plan->plan_id . ' not created (TemplateProcessor Error)');
            }
            else{
                Log::critical('Invoice file not created (TemplateProcessor Error)');
            }
            die;
        }

        !isset($info->company_bank)       ?: $template->setValue('company_bank',$info->company_bank);
        !isset($info->company_name)       ?: $template->setValue('company_name',$info->company_name);
        !isset($info->company_bik)        ?: $template->setValue('company_bik',$info->company_bik);
        !isset($info->company_inn)        ?: $template->setValue('company_inn',$info->company_inn);
        !isset($info->company_bill)       ?: $template->setValue('company_bill',$info->company_bill);
        !isset($info->company_address)    ?: $template->setValue('company_address',$info->company_address);
        !isset($info->company_phone)      ?: $template->setValue('company_phone',$info->company_phone);
        !isset($invoice_number)           ?: $template->setValue('invoice_number',$invoice_number);
                                             $template->setValue('invoice_date', $invoice_date ?? date('d.m.Y'));
        !isset($info->client_inn)         ?: $template->setValue('client_inn', 'ИНН: '.$info->client_inn);
        !isset($info->client_data)        ?: $template->setValue('client_inn', $info->client_data);
        !isset($info->client_name)        ?: $template->setValue('client_name', $info->client_name);
        !isset($info->client_address)     ?: $template->setValue('client_address', $info->client_address);
        !isset($info->client_phone)       ?: $template->setValue('client_phone', 'Телефон: '.$info->client_phone);
        !isset($info->client_site)        ?: $template->setValue('client_site', $info->client_site);
        if(isset($plan->plan_contract_name)) {
                                             $template->setValue('client_contract', $plan->plan_contract_name);}
        elseif(isset($info->invoice_contract)){
                                             $template->setValue('client_contract', $info->invoice_contract);
        }else{                               $template->setValue('client_contract', 'Договор № ' . $info->contract_number . ' от ' . $info->contract_date);

        }

        try {
            $template->cloneRow('service_id', (int)$rows_count);
        } catch (Exception $e) {}

        $total_price = 0;

        for ($index = 1; $index <= $rows_count; $index++) {
            $template->setValue('service_id#' . $index, $index);
            $template->setValue('service_writen#' . $index, $services[$index - 1]->cs_service_writen);
            $template->setValue('service_month#' . $index, Strings::get_month_name($service_month));
            $template->setValue('service_year#' . $index, $service_year);
            $template->setValue(
                'service_price#' . $index,
                number_format((int)$services[$index - 1]->cs_price, 2, ',', '')
            );
            $total_price += (int)$services[$index - 1]->cs_price;
        }

        $price_str = trim(str_replace([$total_price,'(',')'], '', Strings::str_price($total_price)));

        $template->setValue('service_total', number_format($total_price, 2, ',', ''));
        $template->setValue('services_count', $rows_count);
        $template->setValue('service_total_string', $price_str);

        $filename = "Счет_".$invoice_number."_от_".date('d_m_Y') . '.docx';

        $template->saveAs(storage_path('app/public/invoices/' . $filename));
        return $filename;

    }

    /**
     * @return string
     */
    public function send_invoices_queue(): string
    {
        $date = Carbon::now()->toDateString();

        $invoices = InvoiceModel::where('invoice_sended', '=', 0)->where('invoice_date', $date)->get();
        if (!count($invoices)) {
            Log::info('Has no invoices to send [date: '.$date.']');
            return 'Has no invoices to sent';
        }

        foreach ($invoices as $invoice) {
            $details = ['file' => Storage::disk('public')->path('invoices/' . $invoice->invoice_file)];
            $sendJob = (new SendEmail($invoice->invoice_name, $details))->delay(Carbon::now()->addMinute());
            dispatch($sendJob);

            Log::info($invoice->invoice_id.' invoice added to sending queue [time: '.time().']');

            $invoice->update(['invoice_sended'=> 1]);
        }
        Log::info(count($invoices).' invoices have been sent [date: '.$date.']');
        return 'Invoices have been sent';
    }

    /**
     * @return string
     */
    public function send_not_payed_invoices_after_5_days_queue(): string
    {
        $invoices = InvoiceModel::where('invoice_sended', '=', 1)
            ->where('invoice_payed','=',0)
            ->where('invoice_date', '=', Carbon::today()->subDays(5)->toDateString())
            ->get();

        if (!count($invoices)) {
            Log::info('Has no invoices to send [date: '.date('b.m.Y').']');
            return 'Has no invoices to sent';
        }

        foreach ($invoices as $invoice) {

            $sendJob = (new SendMailIfNotPayed5Days(''))->delay(Carbon::now()->addMinute());
            dispatch($sendJob);

            Log::info($invoice->invoice_id.' invoice added to sending queue [time: '.time().']');

            $invoice->update(['invoice_sended'=> 5]);
        }
        Log::info(count($invoices).' invoices have been sent [date: '.$date.']');
        return 'Invoices have been sent';
    }

    /**
     * @return void
     */
    public function send_invoices_check_payed(): void
    {
        $invoices = InvoiceModel::where('invoice_sended', '=', 1)
                            ->where('invoice_payed','=',0)
                            ->whereIn('invoice_date',
                                      [Carbon::today()->subDays(2)->toDateString(),
                                       Carbon::today()->subDays(4)->toDateString()])
                            ->get('invoice_name');
        if (count($invoices))
        {
            Mail::to('arif.settarov@mail.ru')
                   ->send(new SendMailToCheckPayedForAdmin('Есть счета требующие проверки оплаты', (object)['invoices'=>$invoices]));
        }
    }

    /***
     * @return int
     */
    public function update_plans(): int
    {
        $month = Carbon::now()->month;
        $count = (new PlansModel())->where('plan_status', 1)->where('plan_next_update',Carbon::today()->toDateString())
                                                                      ->where('plan_success_this_month',1)->count();
        Log::info('Count: '.$count);
        Log::info('Today: '.Carbon::today()->toDateString());
        if ($month != 2 and $count) {

                (new PlansModel())->where('plan_next_update',Carbon::today()->toDateString())
                    ->where('plan_success_this_month',1)->update(['plan_success_this_month'=>0, 'plan_next_update'=>Carbon::today()->addMonth()->toDateString()]);
                Log::info('Plans have been activated for next month [date: '.Carbon::today()->toDateString().']');
                (new PlansModel())->where('plan_status', 0)->where('plan_next_update',Carbon::today()->toDateString())->update(['plan_next_update'=>Carbon::today()->addMonth()->toDateString()]);
                return 1;
        }

        if ($count) {
            (new PlansModel())->where('plan_status', 1)->where('plan_next_update',Carbon::today()->toDateString())
                ->where('plan_success_this_month',1)->update(['plan_success_this_month'=>0, 'plan_next_update'=>Carbon::today()->year.'-03-30']);
            Log::info('Plans have been activated for next month [date: '.Carbon::today()->toDateString().']');

            (new PlansModel())->where('plan_status', 0)->where('plan_next_update',Carbon::today()->toDateString())->update(['plan_next_update'=>Carbon::today()->year.'-03-30']);
            return 1;
        }

        Log::info('Plans activating passed [date:'.Carbon::today()->toDateString().']');
        return 1;
   }

    /**
     * @param int $invoice_id
     * @return bool
     */
    public function off_invoice_send(int $invoice_id): bool
    {
        $this->model->where('invoice_id', $invoice_id)->update(['invoice_sended'=> 2]);
        return true;
    }

    /**
     * @param int $invoice_id
     * @return bool
     */
    public function check_invoice_payed(int $invoice_id): bool
    {
        $this->model->where('invoice_id', $invoice_id)->update(['invoice_payed'=> 1]);
        return true;
    }


    /**
     * @param int $invoice_id
     * @return mixed
     */
    public function redo_invoice_send(int $invoice_id)
    {
        $invoice = $this->model->find($invoice_id);

        if (strtotime($invoice->invoce_date) < time())
        {
            if (Carbon::now()->hour > 15)
            {
                $send_date = Carbon::today()->addDay();
            }
            else
            {
                $send_date = Carbon::today();
            }

            $invoice->invoice_date = $send_date;
        }
        $invoice->invoice_sended = 0;
        $invoice->invoice_send_active = 1;
        return $invoice->save();
    }

    /**
     * @param $request
     * @return string
     * @throws \Exception
     */
    public function create_custom_invoice($request)
    {
        $supplier = (new CompaniesModel)->where('company_id', $request->invoice_supplies)->firstOrFail();
        $supplier->client_data = $request->client_inn;
        $supplier->client_address = $request->client_address;
        $supplier->client_phone = $request->client_phone;
        $supplier->client_name = $request->client_name;
        $supplier->client_site = '';
        $supplier->invoice_contract = $request->invoice_contract;
        $invoice_number = $this->get_invoice_number();
        $services = array();
        foreach ($request->service_names as $key => $service_name)
        {
            $services[] = (object)['cs_service_writen'=>$service_name, 'cs_price'=>$request->service_prices[$key]];
        }

        if ($request->invoice_date == Carbon::today()->isoFormat('DD.MM.YYYY'))
        {
            $date = Carbon::today();
        }
        else
        {
            $date = Carbon::create($request->invoice_date);
        }
        $invoice = $this->create_invoice($supplier, $invoice_number, count($services), $services, $date->month, $date->year, false, $request->invoice_date);
        $client = ClientsModel::where('client_name', $request->client_name)
                              ->orWhere('client_inn', $request->client_inn)->first();
        $contract = ContractsModel::whereIn('contract_number', explode(' ',$supplier->invoice_contract))->first();

        if (!isset($client->client_id))
        {
            $client_id = 0;
        }
        else{
            $client_id = $client->client_id;
        }
        if (!isset($contract->contract_id))
        {
            $contract_id = 0;
        }
        else{
            $contract_id = $contract->contract_id;
        }


        if($request->send_email)
        {
            $invoice_id = $this->save_invoice_in_db($invoice_number, $client_id, $contract_id,$invoice,Carbon::today()->toDateString(),$request->send_email,0,1);
            $details = ['file' => Storage::disk('public')->path('invoices/' . $invoice)];

            if ($request->send_time == 'now') {
                $sendJob = (new SendEmail($invoice, $details, $request->send_email))->delay(Carbon::now()->addSeconds(3));
                dispatch($sendJob)->onQueue('emails');
            }
            else{
                $time = Carbon::create(Carbon::today()->toDateString().' '.$request->send_time)->unix();

                if (Carbon::now()->unix() >= $time) {

                    $sendJob = (new SendEmail($invoice, $details))->delay(Carbon::now()->addMinute());
                    dispatch($sendJob)->onQueue('emails');
                }
                else{
                    $time1  = new DateTime(Carbon::now()->toDateTimeString());
                    $time2  = new DateTime(date('Y-m-d H:i:s',$time));
                    $beetween = $time1->diff($time2);

                    $minutes = (int)$beetween->format('%h')*3600 + (int)$beetween->format('%i');
                    $sendJob = (new SendEmail($invoice, $details))->delay(Carbon::now()->addMinutes($minutes));
                    dispatch($sendJob)->onQueue('emails');
                }
            }


        }
        else{
            $invoice_id = $this->save_invoice_in_db($invoice_number, $client_id, 0,$invoice,Carbon::today()->toDateString(),'none',3,0);
            if (!$client_id)
            {
                $this->model->where('invoice_id', $invoice_id)->update(['invoice_custom_client'=>$supplier->client_name]);
            }
            if (!$contract_id)
            {
                $this->model->where('invoice_id', $invoice_id)->update(['invoice_custom_contract'=>$supplier->invoice_contract]);
            }
        }

        $service_ids = (new ServicesModel)->whereIn('service_writen', $request->service_names)->get();
        if(count($service_ids)) {
            foreach ($service_ids as $service) {
                (new ServiceInvoiceModel())->create([
                    'si_service_id' => $service->service_id,
                    'si_client_id' => $client_id,
                    'si_invoice_id' => $invoice_id,
                ]);
            }
        }
        else{
            (new ServiceInvoiceModel())->create([
                'si_service_id' => 0,
                'si_client_id' => $client_id,
                'si_invoice_id' => $invoice_id,
            ]);
        }
        if($request->send_email) {
            return true;
        }
        return Storage::disk('public')->url('invoices/'.$invoice);
    }

}
