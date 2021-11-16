<?php

namespace App\Observers;

use App\Modules\Contracts\Models\ContractTypeServicesModel;
use App\Modules\Contracts\Models\ContractTypesModel;
use App\Modules\Settings\Repositories\ActionsRepo;
use Illuminate\Support\Facades\Auth;

class ContractTypesObserver
{


    public function updating(ContractTypesModel $model):void
    {
        $array=[];
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
            $message = 'Тип договора '.$model->ct_name.' [запись ID:'.$model->ct_id.'] изменен!';
            ActionsRepo::new_field(Auth::id(),$message, serialize($array['keys']),serialize($array['fields_names']),serialize($array['before']), serialize($array['after']), 'change');
        }
    }
    /**
     * Handle the ContractsModel "deleted" event.
     *
     * @param ContractTypesModel $contract_type
     * @return void
     */

    public function deleted(ContractTypesModel $contract_type): void
    {
        ContractTypeServicesModel::where('cts_contract_type_id', $contract_type->ct_id)->delete();

        $message = 'Тип договора '.$contract_type->ct_name.' удален';
        ActionsRepo::new_field(Auth::id(),$message, serialize($array['keys']),serialize($array['fields_names']),serialize($array['before']), serialize($array['after']), 'change');
    }
}
