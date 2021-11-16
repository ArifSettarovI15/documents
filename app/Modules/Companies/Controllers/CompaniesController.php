<?php

namespace App\Modules\Companies\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Companies\Repositories\CompaniesRepository;
use App\Modules\Companies\Requests\CompanyStoreRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class CompaniesController extends Controller
{
    /**
     * @var CompaniesRepository $repo
     */
    protected $repo;
    protected $breadcrumbs;

    /**
     * CompaniesController constructor.
     * @var $repo CompaniesRepository
     */
    public function __construct()
    {
        $this->repo = new CompaniesRepository();
        $this->breadcrumbs[] = ['link'=>route('manager.companies.index'), 'text'=>'Поставщики'];
    }

    /**
     * @return View
     */
    public function index(): view
    {
        $companies = $this->repo->get_companies();
        return view('Companies::index', compact('companies'))->with('breadcrumbs', $this->breadcrumbs);
    }

    /**
     * @return View
     */
    public function create(): View
    {
        $this->breadcrumbs[] = ['text'=>'Создание'];
        return view('Companies::create')->with('breadcrumbs', $this->breadcrumbs);
    }

    public function store(CompanyStoreRequest $request):JsonResponse
    {
        $company = $this->repo->new_company($request);
        if ($company and isset($company->company_id))
        {
            return response()->json(['status'=>true, 'message'=>'Поставщик создан успешно']);
        }
        return response()->json(['status'=>false, 'message'=>'Ошибка при сохранении нового поставщика']);
    }

    public function show(int $id)
    {
        $info = $this->repo->get_item($id);
        $this->breadcrumbs[] = ['text'=>'Редактирование '.$info->company_name];
        return view('Companies::create', compact('info'))->with('breadcrumbs', $this->breadcrumbs);
    }

    public function update(CompanyStoreRequest $request, int $id):JsonResponse
    {
        $company = $this->repo->update_item($id, $request);
        if ($company and isset($company->company_id))
        {
            return response()->json(['status'=>true, 'message'=>'Поставщик успешно обновлен']);
        }
        return response()->json(['status'=>false, 'message'=>'Ошибка при обновлении данных поставщика']);
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
            return response()->json('Поставщик успешно удален');
        }
        return response()->json('Ошибка удаления поставщика');
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
