<?php

namespace App\Observers;


use App\Modules\Plans\Models\PlansModel;
use App\Modules\Settings\Repositories\ActionsRepo;
use Illuminate\Support\Facades\Auth;

class PlansObserver
{


    /**
     * @param PlansModel $plan
     */
    public function created(PlansModel $plan): void
    {
        $message = 'Создан новый план отправки <a href="'.route('manager.plans.show', $plan->plan_id).'">План отправки № '.$plan->plan_id.'</a>';
        ActionsRepo::new_field(Auth::id(),$message, '','','', '', 'create');
    }

    /**
     * Handle the PlansModel "updating" event.
     *
     * @param PlansModel $model
     * @return void
     */
    public function updating(PlansModel $model): void
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
            $message = 'План отправки ID №'.$model->plan_id.' изменен!';
            ActionsRepo::new_field(Auth::id(),$message, serialize($array['keys']),serialize($array['fields_names']),serialize($array['before']), serialize($array['after']), 'change');
        }

    }

    /**
     * @param PlansModel $plan
     */
    public function deleted(PlansModel $plan): void
    {
        $message = 'План отправки № '.$plan->plan_id.' удален';
        ActionsRepo::new_field(Auth::id(),$message, '','','', '', 'delete');
    }
}
