<?php


namespace App\Modules\Clients\Repositories;


use App\Modules\Clients\Models\ClientsModel;
use App\Modules\Clients\Requests\ClientsStoreRequest;
use App\Modules\Invoices\Models\InvoiceModel;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;



class ClientsRepository extends BaseRepository
{


    /**
     * @var $model ClientsModel
     */
    public function __construct()
    {
        $this->model = new ClientsModel();
    }

    /**
     * @return LengthAwarePaginator
     */
    public function get_clients(): LengthAwarePaginator
    {
        return $this->model->paginate(15);
    }

    /**
     * @param int $id
     * @return Collection
     */
    public function client_invoices(int $id): Collection
    {
        return InvoiceModel::where('invoice_client_id', '=', $id)->get();
    }


    /**
     * @param ClientsStoreRequest $request
     * @return ClientsModel
     */
    public function new_client(ClientsStoreRequest $request): ClientsModel
    {

        $request->merge([
            'client_phone' => str_replace('+', '', $request->client_phone),
            'client_status' => (int) $request->client_status
            ]);
        return $this->model->create($request->all());
    }

}
