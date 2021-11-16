<?php


namespace App\Modules\Companies\Repositories;

use App\Modules\Companies\Models\CompaniesModel;
use App\Modules\Companies\Requests\CompanyStoreRequest;
use App\Repositories\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class CompaniesRepository extends BaseRepository
{
    /**
     * CompaniesRepository constructor.
     * @var $model CompaniesModel
     */
    public function __construct()
    {
        $this->model = new CompaniesModel();
    }

    /**
     * @return LengthAwarePaginator
     */
    public function get_companies(): LengthAwarePaginator
    {
        return $this->model->paginate(15);
    }


    /**
     * @param CompanyStoreRequest $request
     * @return CompaniesModel
     */
    public function new_company(CompanyStoreRequest $request): CompaniesModel
    {

        $request->merge([
                            'company_phone' => str_replace('+', '', $request->company_phone),
                            'company_status' => (int) $request->company_status
                        ]);
        return $this->model->create($request->all());
    }
}
