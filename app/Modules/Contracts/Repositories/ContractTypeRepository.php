<?php


namespace App\Modules\Contracts\Repositories;


use App\Modules\Contracts\Models\ContractTypeServicesModel;
use App\Modules\Contracts\Models\ContractTypesModel;
use App\Modules\Services\Models\ServicesModel;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class ContractTypeRepository extends BaseRepository
{

    /**
     * ContractTypeRepository constructor.
     * @var BaseRepository::model ContractTypesModel
     */
    public function __construct()
    {
        $this->model = new ContractTypesModel;
    }

    /**
     * @return Collection
     */
    public function get_services(): Collection
    {
        return ServicesModel::where('service_status', 1)->get();
    }

    /**
     * @param int $type_id
     * @param array $services
     * @return mixed
     */
    public function save_type_services(int $type_id, array $services) : Collection
    {

        ContractTypeServicesModel::where('cts_contract_type_id', $type_id)->delete();
        foreach ($services as $service) {
            $cts = new ContractTypeServicesModel();
            $cts->cts_contract_type_id = $type_id;
            $cts->cts_service_id= $service;
            $cts->save();
        }
        return ContractTypeServicesModel::where('cts_contract_type_id', $type_id)->get();
    }

    public function get_selected_services(int $id) : array
    {
        return ContractTypeServicesModel::where('cts_contract_type_id', $id)->pluck('cts_service_id')->toArray();
    }
    public function get_contract_services($contract_type_id):Collection
    {
        return ContractTypeServicesModel::where('cts_contract_type_id',$contract_type_id)
            ->join('services', 'service_id','cts_service_id')
            ->where('services.service_status',1)
            ->get();

    }
}

