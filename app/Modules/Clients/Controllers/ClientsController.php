<?php

namespace App\Modules\Clients\Controllers;

use App\Modules\Clients\Models\ClientsModel;
use App\Modules\Clients\Repositories\ClientEmailsRepository;
use App\Modules\Clients\Repositories\ClientsRepository;
use App\Modules\Clients\Requests\ClientsStoreRequest;
use App\Repositories\GlobalRepository;
use App\View\Components\BreadCrumbs;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Route;


class ClientsController
{

    /**
     * @var ClientsRepository $repo
     */
    protected $repo;
    protected $breadcrumbs =[];

    /**
     * ClientsController constructor.
     * @var $repo ClientsRepository
     */
    public function __construct()
    {
        $this->repo = new ClientsRepository();
        $this->breadcrumbs[] = ['link'=>route('manager.clients.index'), 'text'=>'Клиенты'];
    }

    /**
     * @param Request $request
     * @return Application|Factory|View|JsonResponse
     */
    public function index(Request $request)
    {

        if ($request->ajax())
        {
            $clients = $this->repo->get_items_paginate(15, $request->json()->all(),'desc');
            return response()->json([
                                        'html' => view('Clients::components.clients_table', compact('clients'))->render(),
                                        'paging' => view('paging', ['paginator'=>$clients])->render()
                                    ]);
        }
        $clients = $this->repo->get_clients();

        return view('Clients::index', compact('clients'))->with('breadcrumbs', $this->breadcrumbs);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return Application|Factory|View|JsonResponse
     */
    public function info(Request $request, int $id)
    {


        if ($request->ajax())
        {
            $invoices = GlobalRepository::get_client_invoices($id, $request->json()->all());
            return response()->json([
                'html'=>view('Clients::components.client_invoices_table', compact('invoices'))->render(),
                'paging'=>view('paging')->with('paginator', $invoices)->render()
            ]);
        }

        $info = $this->repo->get_item($id);
        $emails = ClientEmailsRepository::get_emails($id);
        $suppliers = GlobalRepository::get_active_suppliers_list();
        $services = GlobalRepository::get_client_services_list($id);
        $services_select = GlobalRepository::get_services_list($id);
        $client_contracts = GlobalRepository::get_client_contracts_list($id);
        $invoices = GlobalRepository::get_client_invoices($id);
        $this->breadcrumbs[] = ['text'=>$info->client_name];
        return view('Clients::client', compact('info', 'emails', 'invoices', 'suppliers','services', 'client_contracts','services_select'))->with('breadcrumbs', $this->breadcrumbs);
    }

    /**
     * @return View
     */
    public function create(): View
    {
        $this->breadcrumbs[] = ['text'=>'Создание'];
        return view('Clients::create')->with('breadcrumbs', $this->breadcrumbs);
    }

    /**
     * @param ClientsStoreRequest $request
     * @return RedirectResponse|JsonResponse
     * @var ClientsModel $result
     */
    public function store(ClientsStoreRequest $request)
    {
        $result = $this->repo->new_client($request);
        if ($result->client_id){

            return redirect(route('manager.contracts.create'));
        }

        return response()->json("Ошибка создания клиента");
    }

    /**
     * @param $id
     * @return View
     */
    public function show($id): View
    {
        $info = $this->repo->get_item($id);
        $emails = ClientEmailsRepository::get_emails($id);
        $this->breadcrumbs[] = ['text'=>'Редактирование: '.$info->client_name];
        return view('Clients::create', compact('info', 'emails'))->with('breadcrumbs', $this->breadcrumbs);
    }

    /**
     * @param ClientsStoreRequest $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(ClientsStoreRequest $request, int $id) : JsonResponse
    {

        $result = $this->repo->update_item($id, $request);
        if ($result){
            $request->merge(['email_client_id'=>$id]);
            ClientEmailsRepository::save_emails($request);
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
            return response()->json('Клиент успешно удален');
        }
        return response()->json('Ошибка удаления клиента');
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
}
