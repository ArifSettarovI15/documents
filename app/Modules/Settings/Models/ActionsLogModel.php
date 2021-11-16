<?php


namespace App\Modules\Settings\Models;



use App\Modules\User\Models\UserModel;
use App\Modules\User\Models\UserProfileModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SettingsModel
 * @package App\Modules\Settings\Models
 * @mixin Builder
 */
class ActionsLogModel extends Model
{
    protected $table = 'actions_log';
    protected $primaryKey = 'log_id';
    protected $fillable =[
      'log_message',
      'log_type',
      'log_fields_keys',
      'log_fields_names',
      'log_before',
      'log_after',
      'log_time',
      'log_user',
    ];
    public $timestamps = false;

    public function getLogUserNameAttribute(): string
    {
        $user = (new UserProfileModel)->find($this->log_user, 'profile_user_id')->first();
        if (!$user) {
            $user = UserModel::find($this->log_user)->pluck('login')[0];
            if ($user) {
                return $user->login;
            }
            return $this->log_user;
        }
        return $user->profile_name.' '.$user->profile_lastname;
    }
}
