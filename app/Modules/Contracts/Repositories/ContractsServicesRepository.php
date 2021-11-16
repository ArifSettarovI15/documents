<?php


namespace App\Modules\Contracts\Repositories;


use App\Modules\Contracts\Models\ContractsServicesModel;
use App\Repositories\BaseRepository;

class ContractsServicesRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = new ContractsServicesModel();
    }
}
