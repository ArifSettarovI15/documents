<?php

namespace App\Modules\Services\Controllers;


use App\Modules\Services\Models\ServicesModel;
use App\Modules\Services\Repositories\ServicesRepository;
use App\Modules\Services\Requests\ServicesStoreRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServicesController
{

    /**
     * @var ServicesRepository $repo
     */
    protected $repo;
    protected $breadcrumbs;

    /**
     * ServicesController
     * @var $repo ServicesRepository
     */
    public function __construct()
    {
        $this->repo = new ServicesRepository();
        $this->breadcrumbs[] = ['link'=>route('manager.services.index'), 'text'=>'Услуги'];
    }

    /**
     * @return View
     */
    public function index(): View
    {
        $services = $this->repo->get_items_paginate();
        return view('Services::index', compact('services'))->with('breadcrumbs', $this->breadcrumbs);
    }

    /**
     * @return View
     */
    public function create(): View
    {
        $this->breadcrumbs[] = ['text'=>'Создание'];
        return view('Services::create')->with('breadcrumbs', $this->breadcrumbs);
    }

    /**
     * @param ServicesStoreRequest $request
     * @return JsonResponse
     * @var ServicesModel $result
     */
    public function store(ServicesStoreRequest $request): JsonResponse
    {
        $result = $this->repo->new_item($request);
        if ($result){
            return response()->json('Услуга успешно создана');
        }

        return response()->json('Ошибка создания услуги');
    }

    /**
     * @param $id
     * @return View
     */
    public function show($id): View
    {
        $info = $this->repo->get_item($id);
        $this->breadcrumbs[] = ['text'=>'Услуга: '.$info->service_title];
        return view('Services::create', compact('info'))->with('breadcrumbs', $this->breadcrumbs);
    }

    /**
     * @param ServicesStoreRequest $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(ServicesStoreRequest $request, int $id) : JsonResponse
    {
        $result = $this->repo->update_item($id, $request);
        if ($result) {
            return response()->json('Данные успешно обновлены');
        }

        return response()->json('Ошибка обновления данных');
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
            return response()->json('Услуга успешно удалена');
        }
        return response()->json('Ошибка удаления услуги');
    }

    public function status(Request $request) : JsonResponse
    {
        $result = $this->repo->change_status($request);
        if ($result){
            return response()->json(['status'=>true, 'message'=>'Статус успешно изменен']);
        }
        return response()->json(['status'=>false, 'message'=>'Ошибка при изменении статуса']);

    }
}
