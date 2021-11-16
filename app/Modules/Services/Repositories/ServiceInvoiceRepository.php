<?php


namespace App\Modules\Services\Repositories;


use App\Modules\Services\Models\ServiceInvoiceModel;
use App\Repositories\BaseRepository;

class ServiceInvoiceRepository extends  BaseRepository
{
    public function __construct()
    {
        $this->model = new ServiceInvoiceModel();
    }
}
