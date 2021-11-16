<?php

namespace App\Modules\Contracts\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Clients\Models\ClientsModel;
use App\Modules\Contracts\Models\ContractsModel;
use App\Modules\Contracts\Repositories\ContractsRepository;
use App\Modules\Contracts\Requests\ContractsStoreRequest;
use App\Modules\Plans\Models\PlansModel;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContractsController extends Controller
{
    /**
     * @var ContractsRepository $repo
     */
    private $repo;
    protected $breadcrumbs;
    /**
     * ContractsController constructor.
     */
    public function __construct()
    {
        $this->repo = new ContractsRepository();
        $this->breadcrumbs[] = ['link'=>route('manager.contracts.index'), 'text'=>'Договоры'];
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Application|Factory|View|JsonResponse
     */
    public function index(Request $request)
    {
        if ($request->ajax())
        {
            $contracts = $this->repo->get_items_paginate(15, $request->json()->all(),'desc');
            return response()->json([
                'html' => view('Contracts::components.contracts_table', compact('contracts'))->render(),
                'paging' => view('paging', ['paginator'=>$contracts])->render()
                ]);
        }
        $contracts = $this->repo->get_items_paginate(15,[],'desc');
        $clients = ClientsModel::where('client_status',1)->get();
        return view('Contracts::index', compact('contracts', 'clients'))->with('breadcrumbs', $this->breadcrumbs);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $client
     * @return View
     */
    public function create(Request $client): View
    {
        $contract_types = $this->repo->get_contract_types();
        $services = $this->repo->get_contract_type_services($contract_types[0]);

        $type_info = $this->repo->get_contract_type($contract_types[0]->ct_id);
        $suppliers = $this->repo->get_suppliers();
        if ($client->client_id)
        {
            $clients = $this->repo->get_clients($client->client_id);
        }
        else
        {
            $clients = $this->repo->get_clients();
        }

        $this->breadcrumbs[] = ['text'=>'Создание'];
        return view('Contracts::create', compact('suppliers','type_info','clients', 'contract_types', 'services'))->with('breadcrumbs', $this->breadcrumbs);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ContractsStoreRequest $request
     * @return JsonResponse
     */
    public function store(ContractsStoreRequest $request): JsonResponse
    {
        $array = [];
        if (isset($request->dop_key) and isset($request->dop_val)) {
            foreach ($request->dop_key as $index => $key) {
                $array[$key] = $request->dop_val[$index];
            }
        }

        $request->merge(['contract_dop' => serialize($array)]);

        $result = $this->repo->new_item($request);
        if ($result){
            if (isset($request->cs_service_id, $request->cs_price))
            {
                $this->repo->save_contract_services($result->contract_id, $request->cs_service_id, $request->cs_price,$request->cs_service_period);
            }
            if ($request->plan_date != '')
            {
                if (date('m') != 2) {
                    $date = date('Y-m') . '-30';
                }else{
                    $date = date('Y-m') . '-28';
                }
                PlansModel::create(['plan_day'=>$request->plan_date, 'plan_contract'=>$result->contract_id,'plan_status'=>1, 'plan_next_date'=>$date])->save();
            }

            try {
                $this->repo->create_document($result->contract_id);
            }
            catch (Exception $exception)
            {
                return response()->json(['status'=>true, 'message'=>'Не удалось сгенерировать файл: '.$exception]);
            }

            return response()->json(['status'=>true, 'redirect'=>route('manager.contracts.show', $result->contract_id),'message'=>'Договор успешно добавлен']);
        }

        return response()->json('Ошибка создания нового договора');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return View
     */
    public function show(int $id): View
    {
        $contract_types = $this->repo->get_contract_types();

        $info = $this->repo->get_item($id);
        if (isset($info->contract_type)) {
            $type_info = $this->repo->get_contract_type($info->contract_type);
        }
        $plan = PlansModel::where('plan_contract',$info->contract_id)->first();
        $services_prices = $this->repo->get_contract_services($id);
        if (isset($info->contract_type)) {
            $services = $this->repo->get_contract_type_services($this->repo->get_contract_type($info->contract_type));
        }
        $suppliers = $this->repo->get_suppliers();
        $clients = $this->repo->get_clients();

        $this->breadcrumbs[] = ['text'=>'Редактирование Договор №'.$info->contract_number.' от '.$info->contract_date];
        return view('Contracts::create', compact('info','plan','type_info', 'suppliers','clients', 'contract_types','services', 'services_prices'))->with('breadcrumbs', $this->breadcrumbs);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ContractsStoreRequest $request
     * @param int $id
     * @return JsonResponse
     * @throws Exception
     */
    public function update(ContractsStoreRequest $request, int $id): JsonResponse
    {
        $array = [];
        if (isset($request->dop_key) and isset($request->dop_val)) {
            foreach ($request->dop_key as $index => $key) {
                $array[$key] = $request->dop_val[$index];
            }
        }

        $request->merge(['contract_dop' => serialize($array)]);
        $result = $this->repo->update_item($id, $request);
        if ($result) {
            $this->repo->save_contract_services($id, $request->cs_service_id, $request->cs_price, $request->cs_service_period);
            $this->repo->create_document($id);
            return response()->json(['status' => true, 'message' => 'Данные успешно обновлены']);
        }

        return response()->json(['status' => false, 'message' => 'Ошибка обновления данных']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $result = $this->repo->delete_item($id);
        if ($result)
        {
            return response()->json('Договор успешно удален');
        }
        return response()->json('Ошибка удаления договора');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function status(Request $request) : JsonResponse
    {
        $result = $this->repo->change_status($request);
        if ($result){

            return response()->json(['status'=>true, 'message'=>'Статус успешно изменен']);
        }
        return response()->json(['status'=>false, 'message'=>'Ошибка при изменении статуса']);

    }

    public function convert_to_doc(int $id): JsonResponse
    {
        $contract = $this->repo->get_item($id);
        /** @var ContractsModel $contract */
        $result = $this->repo->convert_to_doc($contract);
        return response()->json($result);

    }
    public function send_to_client(Request $request, int $id): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
            $request->merge($data);
        } catch (Exception $e) {
            return response()->json('Не удалось обработать JSON');
        }

        $result = $this->repo->send_contract_to_client($request, $id);
        if ($result)
        {
            return response()->json(['status'=>true, 'message'=>'Договор успешно отправлен клиенту']);
        }
        return response()->json(['status'=>false, 'message'=>'Не удалось отправить письмо клиенту']);

    }
}
