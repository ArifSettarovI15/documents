<?php


namespace App\Modules\Contracts\Repositories;


use App\Mail\SendContractToClient;
use App\Modules\Clients\Models\ClientsEmailsModel;
use App\Modules\Clients\Models\ClientsModel;
use App\Modules\Companies\Models\CompaniesModel;
use App\Modules\Contracts\Models\ContractsModel;
use App\Modules\Contracts\Models\ContractsServicesModel;
use App\Modules\Contracts\Models\ContractTypeServicesModel;
use App\Modules\Contracts\Models\ContractTypesModel;

use App\Modules\Files\Repositories\FilesRepository;
use App\Repositories\BaseRepository;
use App\Repositories\Functions\NCLNameCaseRu;
use App\Repositories\Functions\Strings;
use CloudConvert\CloudConvert;
use CloudConvert\Models\Job;
use CloudConvert\Models\Task;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Mail;
use Illuminate\View\Factory;
use Storage;

class ContractsRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = new ContractsModel();
    }

    /**
     * @param int|null $id
     * @return Collection
     */
    public function get_clients(int $id = null): Collection
    {
        if ($id)
        {
            return ClientsModel::where('client_id', $id)->get();
        }

        return ClientsModel::where('client_status', 1)->get();
    }

    /**
     * @return Collection
     */
    public function get_suppliers(): Collection
    {
        return CompaniesModel::where('company_status', 1)->get();
    }

    /**
     * @return Collection
     */
    public function get_contract_types(): Collection
    {
        return ContractTypesModel::where('ct_status', 1)->get();
    }

    /**
     * @param int $id
     * @return ContractTypesModel
     */
    public function get_contract_type(int $id): ContractTypesModel
    {
        return ContractTypesModel::where('ct_id', $id)->first();
    }

    /**
     * @param ContractTypesModel $contract_type
     * @return Collection
     */
    public function get_contract_type_services(ContractTypesModel $contract_type): Collection
    {
        return ContractTypeServicesModel::where('cts_contract_type_id', $contract_type->ct_id)
            ->join('services', 'service_id', 'cts_service_id')
            ->where('services.service_status', 1)
            ->get();
    }

    public function save_contract_services(int $id, array $service_ids, array $service_prices, array $service_period): void
    {
        ContractsServicesModel::where('cs_contract_id', $id)->delete();
        foreach ($service_ids as $key => $service_id) {
            $contracts_services = new ContractsServicesModel();
            $contracts_services->cs_contract_id = $id;
            $contracts_services->cs_service_id = $service_id;
            $contracts_services->cs_price = $service_prices[$key];
            $contracts_services->cs_service_period = $service_period[$key];
            $contracts_services->save();
        }
    }

    /**
     * @param mixed $id
     * @return mixed
     */
    public function get_contract_services($id)
    {
        return ContractsServicesModel::where('cs_contract_id', $id)
            ->pluck('cs_price', 'cs_service_id')
            ->toArray();
    }

    public function create_document($contract_id): void
    {
        $contract = ContractsModel::where('contract_id', '=', $contract_id)
            ->join('clients', 'client_id', 'contract_client')
            ->join('companies', 'company_id', 'contract_supplier')
            ->join('contract_types', 'contracts.contract_type', 'contract_types.ct_id')
            ->first();

        $contract->company_full_name = Strings::prepare_company_name($contract->company_name,$contract->company_director);
        $contract->client_full_name = Strings::prepare_company_name($contract->client_name, $contract->client_director);
        $contract->contract_services_price = ContractsServicesModel::where('cs_contract_id', $contract->contract_id)->
        where('cs_service_period','=','monthly')->sum('cs_price');



        $contract->client_director_rod = (new NCLNameCaseRu())->q($contract->client_director, 1);
        $contract->company_signature = Strings::name_for_signature($contract->company_director);
        $contract->client_signature = Strings::name_for_signature($contract->client_director);

        $contract->contract_dop = unserialize($contract->contract_dop);

        $contract->contract_date_top = Strings::ru_date('', strtotime($contract->contract_date));


        $template_blade = file_get_contents(FilesRepository::getFilePath($contract->ct_template));
        $contract['__env'] = app(Factory::class);
        $contract['Strings'] = app(Strings::class);
        $template_html = Blade::compileString($template_blade);
        ob_start() and extract($contract->toArray(), EXTR_SKIP);

        try {
            eval('?>' . $template_html);
        } catch (Exception $e) {
            ob_get_clean();
            throw $e;
        }
        $template_html = ob_get_clean();

        $filename = $contract->contract_number.'_'.$contract->client_name.'_'.$contract->contract_date;
        $file_id = FilesRepository::save_file($template_html,'contracts', $filename,'html');
        ContractsModel::where('contract_id', '=', $contract->contract_id)->update(['contract_file_id'=>$file_id]);
    }

    public function convert_to_doc(ContractsModel $contract): array
    {
        $api_key = env('CLOUDCONVERT_API_KEY', '');
        $path_html = $contract->contract_file_path;
        $path_array = explode('/',$path_html);
        $basename = str_replace(".html", '', last($path_array));
        $basename = Strings::translit($basename);
        $path_docx = 'contracts/'.$basename.'.docx';


        $cloudconvert = new CloudConvert(['api_key' => $api_key, 'sandbox'=>false]);
        $job = (new Job())
            ->addTask(
                (new Task('import/upload', 'import-1'))
            )
            ->addTask(
                (new Task('convert', 'task-1'))
                    ->set('input_format', 'html')
                    ->set('output_format', 'docx')
                    ->set('engine', 'office')
                    ->set('input', ["import-1"])
                    ->set('embed_images', false)
            )
            ->addTask(
                (new Task('export/url', 'export-1'))
                    ->set('input', ["task-1"])
                    ->set('inline', false)
                    ->set('archive_multiple_files', false)
            );


        $cloudconvert->jobs()->create($job);
        $task = $job->getTasks()->whereName('import-1')[0];

        $filestream = fopen($path_html, 'rb');
        $cloudconvert->tasks()->upload($task, $filestream);
        $cloudconvert->jobs()->wait($job); // Wait for job completion

        foreach ($job->getExportUrls() as $file) {
            $source = $cloudconvert->getHttpTransport()->download($file->url)->detach();
            if (!file_exists(Storage::disk('public')->path($path_docx))) {
                touch(Storage::disk('public')->path($path_docx));
            }
            $dest = fopen(Storage::disk('public')->path($path_docx), 'wb');

            stream_copy_to_stream($source, $dest);
        }

        $file = FilesRepository::add_row_to_db($basename ,'docx','contracts');

        $contract->update(['contract_doc_file_id'=> $file->file_id]);
        return ['name'=>$contract->contract_doc_file_name, 'url'=>$contract->contract_doc_file_url];
    }

    public function send_contract_to_client(Request $request, int $contract):bool
    {
        $contract = $this->get_item($contract);
        $details = ['contract_file'=>$contract->contract_doc_file_path];
        Mail::to($request->email_ids)->send(new SendContractToClient('Договор на поставку услуг', $details));

        return true;
    }

}
