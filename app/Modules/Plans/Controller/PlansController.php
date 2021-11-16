<?php

namespace App\Modules\Plans\Controller;

use App\Http\Controllers\Controller;
use App\Modules\Plans\Repositories\PlansRepository;
use App\Modules\Plans\Requests\PlansStoreRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;


class PlansController extends Controller
{

    /**
     * @var PlansRepository $repo
     */
    protected $repo;

    protected $breadcrumbs;

    /**
     * PlansController constructor.
     */
    public function __construct()
    {
        $this->repo = new PlansRepository();
        $this->breadcrumbs[] = ['link'=>route('manager.plans.index'), 'text'=>'Счета'];
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $plans = $this->repo->get_items_paginate();
        return view('Plans::index', compact('plans'))->with('breadcrumbs', $this->breadcrumbs);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        $contracts = $this->repo->get_contracts();
        $this->breadcrumbs[] = ['text'=>'Создание'];
        return view('Plans::create', compact('contracts'))->with('breadcrumbs', $this->breadcrumbs);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PlansStoreRequest $request
     * @return JsonResponse
     */
    public function store(PlansStoreRequest $request):JsonResponse
    {
        if (date('m') != 2) {
            $date = date('Y-m') . '-30';
        }else{
            $date = date('Y-m') . '-28';
        }
        $request->merge(['plan_next_update'=>$date]);
        $result = $this->repo->new_item($request);

        if ($result->plan_id) {
                return response()->json('План успешно создан');
            }

        return response()->json('Ошибка создания нового плана');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return View
     */
    public function show(int $id): view
    {
        $contracts = $this->repo->get_contracts();
        $info = $this->repo->get_item($id);
        $this->breadcrumbs[] = ['text'=>'План #'.$info->plan_id];
        return view('Plans::create', compact('info', 'contracts'))->with('breadcrumbs', $this->breadcrumbs);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PlansStoreRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(PlansStoreRequest $request, int $id): JsonResponse
    {
        $result = $this->repo->update_item($id, $request);
        if ($result)
        {
            return response()->json('План успешно обновлен');
        }
        return response()->json('Ошибка обновления плана');
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
            return response()->json('План успешно удален');
        }
        return response()->json('Ошибка удаления плана');
    }

    /**
     * @param $request
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
}
