<?php

namespace App\Observers;

use App\Modules\Companies\Models\CompaniesModel;
use App\Modules\Contracts\Models\ContractsModel;
use App\Modules\Settings\Repositories\ActionsRepo;
use Illuminate\Support\Facades\Auth;

class SuppliersObserver
{
    /**
     * @param CompaniesModel $company
     */
    public function created(CompaniesModel $company): void
    {
        $message = 'Создан новый поставщик услуг <a href="'.route('manager.companies.show', $company->company_id).'">'.$company->company_name.'</a>';
        ActionsRepo::new_field(Auth::id(),$message, '','','', '', 'create');
    }
    /**
     * Handle the CompaniesModel "updating" event.
     *
     * @param CompaniesModel $model
     * @return void
     */
    public function updating(CompaniesModel $model): void
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
            $message = 'Поставщик услуг '.$model->company_name.' [запись ID:'.$model->company_id.'] изменен!';
            ActionsRepo::new_field(Auth::id(),$message, serialize($array['keys']),serialize($array['fields_names']),serialize($array['before']), serialize($array['after']), 'change');
        }

    }

    /**
     * Handle the CompaniesModel "deleted" event.
     * @param CompaniesModel $company
     */
    public function deleted(CompaniesModel $company):void
    {
        ContractsModel::where('contract_supplier', $company->company_id)->update(['contract_status' =>0, 'contract_supplier'=> 0]);

        $message = 'Поставщик услуг '.$company->company_name.' удален';
        ActionsRepo::new_field(Auth::id(),$message, '','','', '', 'delete');
    }
}
