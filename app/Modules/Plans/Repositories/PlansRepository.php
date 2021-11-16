<?php


namespace App\Modules\Plans\Repositories;


use App\Modules\Contracts\Models\ContractsModel;
use App\Modules\Plans\Models\PlansModel;
use App\Repositories\BaseRepository;
use Illuminate\Support\Collection;

class PlansRepository extends BaseRepository
{
    /**
     * PlansRepository constructor.
     *
     */
    public function __construct()
    {
        $this->model = new PlansModel();
    }

    /**
     * @return Collection
     */
    public function get_contracts(): Collection
    {
        return ContractsModel::where('contract_status', 1)->get();
    }
}
