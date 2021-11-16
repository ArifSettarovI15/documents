<?php

namespace App\Observers;

use App\Modules\Contracts\Models\ContractsServicesModel;
use App\Modules\Services\Models\ServicesModel;
use App\Modules\Settings\Repositories\ActionsRepo;
use Illuminate\Support\Facades\Auth;

class ServicesObserver
{

    /**
     * @param ServicesModel $service
     */
    public function created(ServicesModel $service): void
    {
        $message = 'Создана новая услуга <a href="'.route('manager.services.show', $service->service_id).'">'.$service->service_title.'</a>';
        ActionsRepo::new_field(Auth::id(),$message, '','','', '', 'create');
    }

    /**
     * @param ServicesModel $model
     */
    public function updating(ServicesModel $model):void
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
            $message = 'Услуга '.$model->service_title.' [запись ID:'.$model->service_id.'] изменена!';
            ActionsRepo::new_field(Auth::id(),$message, serialize($array['keys']),serialize($array['fields_names']),serialize($array['before']), serialize($array['after']), 'change');
        }
    }
    /**
     * Handle the ServicesModel "deleted" event.
     *
     * @param ServicesModel $service
     * @return void
     */
    public function deleted(ServicesModel $service): void
    {
        ContractsServicesModel::where('cs_service_id', $service->service_id)->delete();

        $message = 'Услуга '.$service->service_title.' удалена';
        ActionsRepo::new_field(Auth::id(),$message, '','','', '', 'delete');
    }

}
