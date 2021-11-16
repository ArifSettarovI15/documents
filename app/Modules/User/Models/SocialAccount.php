<?php


namespace App\Modules\User\Models;
use Illuminate\Database\Eloquent\Model;


/**
 * @method static where()
 */
class SocialAccount extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Modules\User\Models\UserModel');
    }
}
