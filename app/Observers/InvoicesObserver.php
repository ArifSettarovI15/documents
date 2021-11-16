<?php

namespace App\Observers;

use App\Modules\Invoices\Models\InvoiceModel;
use App\Modules\Settings\Repositories\ActionsRepo;
use Illuminate\Support\Facades\Auth;

class InvoicesObserver
{
    /**
     * Handle the InvoiceModel "created" event.
     *
     * @param InvoiceModel $model
     * @return void
     */
    public function created(InvoiceModel $model): void
    {
        $message = 'Создан новый счет: '.$model->invoice_name.' [запись ID:'.$model->invoice_id.']!';
        $user = Auth::id();
        if (!$user)
        {
            $user = 0;
        }
        ActionsRepo::new_field($user,$message, '','','', '', 'create');
    }

    /**
     * Handle the InvoiceModel "updating" event.
     *
     * @param InvoiceModel $model
     * @return void
     */
    public function deleted(InvoiceModel $model): void
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
            $message = $model->invoice_name.' [запись ID:'.$model->invoice_id.'] удален!';
            ActionsRepo::new_field(Auth::id(),$message, '','','', '', 'delete');
        }
    }
}
