<?php


namespace App\Repositories;


use App\Modules\Clients\Models\ClientsModel;
use App\Modules\Companies\Models\CompaniesModel;
use App\Modules\Contracts\Models\ContractsModel;
use App\Modules\Services\Models\ServiceInvoiceModel;
use App\Modules\Services\Models\ServicesModel;
use Illuminate\Database\Eloquent\Collection;

class GlobalRepository
{
    /**
     * @return Collection
     */
    public static function get_active_contracts_list():Collection
    {
        return (new ContractsModel())->where('contract_status', '=',1)->get();
    }
    /**
     * @return Collection
     */
    public static function get_client_contracts_list($client_id):Collection
    {
        return (new ContractsModel())->
                    where('contract_client', '=',$client_id)->get();
    }
/**
     * @return Collection
     */
    public static function get_active_clients_list():Collection
    {
        return (new ClientsModel())->where('client_status', 1)->get();
    }

    /**
     * @return Collection
     */
    public static function get_active_services_list():Collection
    {
        return (new ServicesModel())->where('service_status', '=',1)->get();
    }

    /**
     * @param int $client_id
     * @return Collection
     */
    public static function get_client_services_list(int $client_id):Collection
    {
        return (new ServicesModel())->where('service_status', '=',1)
                        ->join('service_invoice', 'si_service_id', '=', 'service_id', 'left')
                        ->where('si_client_id','=', $client_id)
                        ->groupBy('services.service_id')->get();

    }

    /**
     * @param int $client_id
     * @return Collection
     */
    public static function get_services_list(int $client_id):Collection
    {
        return (new ServicesModel())->where('service_status', '=',1)->get();
    }
    /**
     * @return Collection
     */
    public static function get_active_suppliers_list():Collection
    {
        return (new CompaniesModel)->where('company_status', '=',1)->get();
    }

    public static function get_client_invoices($client_id, $filters=[])
    {
        $data = ServiceInvoiceModel::query();
        if (count($filters))
        {
            foreach($filters as $field_name=>$filter) {
                if ($field_name != 'page') {
                    if ($field_name == 'invoice_date') {
                        $filter['value'] = date('Y-m-d', strtotime($filter['value']));
                    }
                    if ($filter['type'] == 'search') {
                        $data->where($field_name, 'LIKE', '%' . $filter['value'] . '%');
                    } elseif ($filter['type'] == 'filter') {
                        $data->where($field_name, '=', $filter['value']);
                    } elseif ($filter['type'] == 'less') {
                        $data->where($field_name, '<=', $filter['value']);
                    } elseif ($filter['type'] == 'more') {
                        $data->where($field_name, '>=', $filter['value']);
                    }
                }
            }
        }
        $data->where('si_client_id', '=', $client_id);
        $data->join('services', 'si_service_id', '=', 'service_id', 'left');
        $data->join('invoices', 'si_invoice_id', '=', 'invoice_id');
        $data->groupBy('invoices.invoice_id');
        return $data->paginate(15);
    }

}

