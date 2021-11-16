<?php


namespace App\Modules\Contracts\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\Contracts\Repositories\ContractsRepository;
use App\Modules\Contracts\Repositories\ContractTypeRepository;
use App\Modules\Contracts\Requests\ContractTypeStoreRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContractTypesController extends Controller
{
    /**
     * @var ContractTypeRepository $repo
     */
    protected $repo;

    /**
     * ContractTypesController constructor.
     * @var $repo ContractTypeRepository
     */
    public function __construct()
    {
        $this->repo = new ContractTypeRepository();
        $this->breadcrumbs[] = ['link'=>route('manager.contracts.index'), 'text'=>'Договоры'];
        $this->breadcrumbs[] = ['link'=>route('manager.contract_types.index'), 'text'=>'Шаблоны'];
    }

    /**
     * @return View
     */
    public function index() : View
    {
        $contract_types= $this->repo->get_items_paginate();
        return view('Contracts::types_index', compact('contract_types'))->with('breadcrumbs', $this->breadcrumbs);
    }

    /**
     * @return View
     */
    public function create() : View
    {
        $services = $this->repo->get_services();
        $this->breadcrumbs[] = ['text'=>'Создание'];
        return view('Contracts::type_create', compact('services'))->with('breadcrumbs', $this->breadcrumbs);
    }

    /**
     * @param ContractTypeStoreRequest $request
     * @return JsonResponse
     */
    public function store(ContractTypeStoreRequest $request): JsonResponse
    {
        $request->merge(
            [
                'ct_dop_keys' => serialize($request->ct_dop_keys),
                'ct_dop_names' => serialize($request->ct_dop_names),
                'ct_dop_tooltips' => serialize($request->ct_dop_tooltips),
            ]);

        $result = $this->repo->new_item($request);

        if (isset($result->ct_id) and isset($request->ct_services))
        {
            $this->repo->save_type_services($result->ct_id, $request->ct_services);
            return response()->json(['status'=>true, 'message'=>'Тип договора успешно создан']);
        }
        return response()->json(['status'=>false, 'message'=>'Ошибка при сохранении типа договоры']);
    }

    /**
     * @param int $id
     * @return View
     */
    public function show(int $id) : View
    {

        $services = $this->repo->get_services();
        $selected_services = $this->repo->get_selected_services($id);
        $info = $this->repo->get_item($id);
        $info->ct_dop_keys = unserialize($info->ct_dop_keys);
        $info->ct_dop_names = unserialize($info->ct_dop_names);
        $info->ct_dop_tooltips = unserialize($info->ct_dop_tooltips);

        $this->breadcrumbs[] = ['text'=>'Редактирование '.$info->ct_name];
        return view('Contracts::type_create', compact('info', 'services', 'selected_services'))->with('breadcrumbs', $this->breadcrumbs);
    }

    /**
     * @param ContractTypeStoreRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function update(ContractTypeStoreRequest $request, $id) : JsonResponse
    {

        $request->merge(
            [
                'ct_dop_keys' => serialize($request->ct_dop_keys),
                'ct_dop_names' => serialize($request->ct_dop_names),
                'ct_dop_tooltips' => serialize($request->ct_dop_tooltips),
            ]);




        $result = $this->repo->update_item($id, $request);

        if ($result)
        {
            if (isset($request->ct_services))
            {
                $this->repo->save_type_services($id, $request->ct_services);
            }
            return response()->json(['status'=>true, 'message'=>'Тип договора успешно обновлен']);
        }
        return response()->json(['status'=>false, 'message'=>'Ошибка обновления типа договора']);
    }
    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id) : JsonResponse
    {
        $result = $this->repo->delete_item($id);
        return response()->json([$result]);

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

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get_services(Request $request): JsonResponse
    {
        $services_prices = null;

        if (isset($request->type_id)) {
            $type_info = $this->repo->get_item($request->type_id);
        }
            $services = $this->repo->get_contract_services((int)$request->type_id);
        if (isset($request->contract_id)) {
            $services_prices = (new ContractsRepository)->get_contract_services($request->contract_id);
        }
        $html = view('Contracts::service_contracts', compact('services' , 'services_prices'))->render();
        $html2 = view('Contracts::components.dop_fields', compact('type_info'))->render();
        return response()->json(['html'=>$html, 'html2'=>$html2]);
    }
}
