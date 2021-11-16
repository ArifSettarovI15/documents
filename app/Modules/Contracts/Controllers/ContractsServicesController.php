<?php
namespace App\Modules\Contracts\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Contracts\Repositories\ContractsServicesRepository;
use App\Modules\Contracts\Requests\ContractServicesStoreRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContractsServicesController extends Controller
{
    /**
     * @var ContractsServicesRepository $repo
     */
    protected $repo;

    /**
     * ContractsServicesController constructor.
     */
    public function __construct()
    {
        $this->repo = new ContractsServicesRepository();
    }

    /**
     * @param ContractServicesStoreRequest $request
     * @return JsonResponse
     */
    public function store(ContractServicesStoreRequest $request): JsonResponse
    {
        $result = $this->repo->new_item($request);

        if ($result->cs_id)
        {
            return response()->json('Услуга успешно добавлена');
        }

        return response()->json('Ошибка добавления услуги');
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $result = $this->repo->update_item($id, $request);
        if ($result)
        {
            return response()->json('Услуга успешно обновлена');
        }
        return response()->json('Ошибка обновления услуги');
    }

    public function destroy(int $id): JsonResponse
    {
        $result = $this->repo->delete_item($id);
        if ($result)
        {
            return response()->json('Услуга успешно удалена');
        }
        return response()->json('Ошибка удаления услуги');
    }

}
