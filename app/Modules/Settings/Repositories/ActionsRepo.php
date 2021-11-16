<?php


namespace App\Modules\Settings\Repositories;


use App\Modules\Settings\Models\ActionsLogModel;
use App\Repositories\BaseRepository;

class ActionsRepo extends BaseRepository
{
    public function __construct()
    {
        $this->model = new ActionsLogModel();
    }

    public static function new_field(int $user_id, string $message, string $fields_keys, string $fields_names, string $before,string $after, string $type): void
    {
        $log = new ActionsLogModel();
        $log->log_user = $user_id;
        $log->log_message = $message;
        $log->log_fields_keys = $fields_keys;
        $log->log_fields_names = $fields_names;
        $log->log_before = $before;
        $log->log_after = $after;
        $log->log_type = $type;
        $log->log_time = time();
        $log->save();
    }

    public static function checkIsDirty($model, $key)
    {
        if ($model->isDirty($key))
        {
            return ['field_name'=>$model->getFieldName($key) ?? $key, 'before'=> $model->getOriginal($key), 'after'=>$model[$key]];
        }
        return false;
    }
}
