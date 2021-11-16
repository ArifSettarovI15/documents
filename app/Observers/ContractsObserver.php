<?php

namespace App\Observers;

use App\Modules\Contracts\Models\ContractsModel;
use App\Modules\Contracts\Models\ContractsServicesModel;
use App\Modules\Plans\Models\PlansModel;
use App\Modules\Settings\Repositories\ActionsRepo;
use Illuminate\Support\Facades\Auth;

class ContractsObserver
{

    /**
     * @param ContractsModel $contract
     */
    public function created(ContractsModel $contract): void
    {
        $message = 'Создан новый договор <a href="'.route('manager.contracts.show', $contract->contract_id).'">'.$contract->contract_name.'</a>';
        ActionsRepo::new_field(Auth::id(),$message, '','','', '', 'create');
    }
    /**
     * Handle the ContractsModel "updating" event.
     *
     * @param ContractsModel $model
     * @return void
     */
    public function updating(ContractsModel $model):void
    {
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
            $message = 'Договор '.$model->contract_number.' [запись ID:'.$model->contract_id.'] изменен!';
            ActionsRepo::new_field(Auth::id(),$message, serialize($array['keys']),serialize($array['fields_names']),serialize($array['before']), serialize($array['after']), 'change');
        }

    }

    /**
     * @param ContractsModel $contract
     * @return void
     */
    public function deleted(ContractsModel $contract): void
    {
        ContractsServicesModel::where('cs_contract_id', $contract->contract_id)->delete();
        PlansModel::where('plan_contract', $contract->contract_id)->delete();

        $message = 'Договор '.$contract->contract_name.' удален';
        ActionsRepo::new_field(Auth::id(),$message, '','','', '', 'delete');
    }
}
