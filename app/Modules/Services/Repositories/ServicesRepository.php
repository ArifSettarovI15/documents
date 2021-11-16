<?php


namespace App\Modules\Services\Repositories;



use App\Modules\Services\Models\ServicesModel;
use App\Repositories\BaseRepository;

class ServicesRepository extends BaseRepository
{
    /**
     * @var $model ServicesModel
     */
    public function __construct()
    {
        $this->model = new ServicesModel();
    }

}
