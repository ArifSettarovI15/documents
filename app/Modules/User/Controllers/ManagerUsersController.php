<?php


namespace App\Modules\User\Controllers;


use App\Modules\User\Repositories\UserRepo;
use App\Modules\User\Requests\UserStoreRequest;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class ManagerUsersController
{
    /**
     * @var $repo UserRepo
     */
    protected $repo;
    protected $breadcrumbs;

    /**
     * ManagerUsersController constructor.
     * @var UserRepo $repo
     */
    public function __construct()
    {
        $this->repo = new UserRepo();
        $this->breadcrumbs[] = ['link'=>route('manager.users.index'), 'text'=>'Пользователи'];
    }

    public function index(){

        $users = $this->repo->get_items_paginate(15, null,'desc');

        return view('User::Manager.index', compact('users'))->with('breadcrumbs', $this->breadcrumbs);
    }

    /**
     * @return View
     */
    public function create(): view
    {

        $this->breadcrumbs[] = ['text'=>'Создание'];
        return view('User::Manager.create')->with('breadcrumbs', $this->breadcrumbs);
    }

    /**
     * @param UserStoreRequest $request
     * @return JsonResponse
     */
    public function store(UserStoreRequest $request): JsonResponse
    {
        $request->merge(['active'=>1, 'regtime'=>time()]);
        $result = $this->repo->new_item($request);
        if ($result->id)
        {
            return response()->json(['status'=>true, 'message'=>"Пользователь создан успешно"]);
        }
        return response()->json(['status'=>false, 'message'=>"Не удалось создать пользователя"]);
    }

    /**
     * @param int $id
     * @return View
     */
    public function show(int $id):view
    {
        $user = $this->repo->get_item($id);

        $this->breadcrumbs[] = ['text'=>'Пользователь '.$user->login];
        return view('User::Manager.create', compact('user'))->with('breadcrumbs', $this->breadcrumbs);
    }

    /**
     * @param UserStoreRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UserStoreRequest $request, int $id): JsonResponse
    {
        $user = $this->repo->get_item($id);

        if ($user->password != $request->password) {
            $request->merge(['password' => Hash::make($request->password.$user->salt)]);
        }
        $result = $this->repo->update_item($id, $request);
        if ($result){
            return response()->json(['status'=>true, 'message'=>"Пользователь успешно обновлен"]);
        }
        return response()->json(['status'=>false, 'message'=>"Не удалось обновить пользователя"]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function status(Request $request): JsonResponse
    {
        $result = $this->repo->change_status($request);
        if ($result){
            return response()->json(['status'=>true, 'message'=>"Пользователь успешно обновлен"]);
        }
        return response()->json(['status'=>false, 'message'=>"Не удалось обновить пользователя"]);
    }


}
