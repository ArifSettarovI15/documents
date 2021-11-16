<?php /** @noinspection ReturnTypeCanBeDeclaredInspection */

/** @noinspection SenselessMethodDuplicationInspection */

namespace App\Modules\User\Models;

use App\Modules\User\Notifications\ResetPasswordNotification;
use App\Modules\User\Notifications\VerifyNotification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;


/**

 * @method create($all)
 * @property string user_login
 * @property string user_password
 * @property string user_salt
 * @property string email
 * @mixin Builder
 */
class UserModel extends Authenticatable implements MustVerifyEmail
{

    use Notifiable;

    public $table = 'users';

    protected $primaryKey = 'id';

    protected $fillable = ['id','login', 'email', 'password', 'active','regtime', 'salt', 'role_id'];

    protected $visible = ['id','login', 'email'];

    protected $hidden = [ 'password', 'salt', 'remember_token'];

    public $timestamps = false;

    /**
     * @return string
     */
    public function getEmailForVerification(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getEmailForPasswordReset(): string
    {
        return $this->email;
    }


    public function sendEmailVerificationNotification()
    {
      $this->notify(new VerifyNotification);
    }
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification);
    }


}
