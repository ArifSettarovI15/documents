<?php

namespace App\Observers;


use App\Modules\Clients\Models\ClientsModel;
use App\Modules\Contracts\Models\ContractsModel;
use App\Modules\Contracts\Models\ContractsServicesModel;
use App\Modules\Plans\Models\PlansModel;
use App\Modules\Settings\Repositories\ActionsRepo;
use Illuminate\Support\Facades\Auth;

class ClientsObserver
{
    /**
     * @param ClientsModel $client
     */
    public function created(ClientsModel $client): void
    {
        $message = 'Создан новый клиент <a href="'.route('manager.clients.show', $client->client_id).'">'.$client->client_name.'</a>';
        ActionsRepo::new_field(Auth::id(),$message, '','','', '', 'create');
    }
    /**
     * Handle the ClientsModel "updating" event.
     *
     * @param ClientsModel $model
     * @return void
     */
    public function updating(ClientsModel $model): void
    {
        $array = [];


        foreach ($model->attributesToArray() as $key=>$value)
        {
            $check = ActionsRepo::checkIsDirty($model, $key);
            if($check)
            {
                $array['fields_names'][] = $check['field_name'];
                $array['before'][] = $check['before'];
                $array['keys'][] = $key;
                $array['after'][] = $check['after'];
            }
        }
        if (count($array['before']) && count($array['after'])) {
            $message = 'Клиент '.$model->client_name.' [запись ID:'.$model->contract_id.'] изменен!';
            ActionsRepo::new_field(Auth::id(),$message, serialize($array['keys']),serialize($array['fields_names']),serialize($array['before']), serialize($array['after']), 'change');
        }

    }

    /**
     * Handle the ClientsModel "deleted" event.
     *
     * @param ClientsModel $client
     * @return bool
     */
    public function deleted(ClientsModel $client): bool
    {
        $contracts = ContractsModel::where('contract_client', $client->client_id)->get();

        foreach ($contracts as $contract)
        {
            ContractsServicesModel::where('cs_contract_id', $contract->contract_id)->delete();
            PlansModel::where('plan_contract', $contract->contract_id)->delete();
            ContractsModel::where('contract_id', $contract->contract_id)->delete();
        }
        $message = 'Клиент '.$client->client_name.' удален';
        ActionsRepo::new_field(Auth::id(),$message, '','','', '', 'delete');
        return true;
    }
}
