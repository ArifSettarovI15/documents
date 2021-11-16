<?php

namespace App\Modules\Manager\Controllers;
use App\Http\Controllers\Controller;

use App\Modules\Dashboard\Repositories\DashboardRepository;
use App\Modules\User\Models\UserModel;
use App\Repositories\GlobalRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use \Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    protected $repo;

    public function __construct()
    {
        $this->repo = new DashboardRepository;
    }

    /**
     * @param Request $request
     * @return Application|Factory|View|JsonResponse
     */
    public function index(Request $request)
    {
        $contracts = GlobalRepository::get_active_contracts_list();
        $services = GlobalRepository::get_active_services_list();
        $suppliers = GlobalRepository::get_active_suppliers_list();
        $actions = $this->repo->get_actions_paginated_filtered(10);
        if ($request->page)
        {
            return response()->json([
                'html'=>view('Manager::logs_table', compact('actions'))->render(),
                'paging'=>view('paging',['paginator'=>$actions])->render()
                ]);
        }

        return view('Manager::index', compact('actions', 'contracts', 'services', 'suppliers'));
    }

    /**
     * @param Request $request
     * @return Application|Factory|View|JsonResponse
     */
    public function log_actions(Request $request)
    {

        if ($request->ajax())
        {
            $actions = $this->repo->get_actions_paginated_filtered(20, $request->json()->all());
            return response()->json([
                                        'html'=>view('Manager::logs_table', compact('actions'))->render(),
                                        'paging'=>view('paging',['paginator'=>$actions])->render()
                                    ]);
        }
        $users = (new UserModel)->join('users_profile', 'profile_user_id', '=', 'users.id')->get();
        $actions = $this->repo->get_actions_paginated_filtered(20);
        return view('Manager::log_actions', compact('actions', 'users'));
    }

    public function get_smtp_status(): JsonResponse
    {
        $result = $this->repo->check_smtp_server();

        return response()->json(['status'=>$result,'target'=>'smtp_status_']);
    }
    public function get_cloudconvert_status(): JsonResponse
    {
        $result = $this->repo->check_convertion_server();
        $result['target'] = 'converter_status_';
        return response()->json($result);
    }

}
