<?php


namespace App\Modules\User\Models;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;


/**
 * @mixin Builder
 */
class UserProfileModel extends Model
{
    protected $table = 'users_profile';
    protected $primaryKey = 'profile_user_id';
    public $timestamps = false;
    protected $fillable =[
        'profile_user_id',
        'profile_name',
        'profile_lastname',
        'profile_phone',
        'profile_payments',
        'profile_bonus',
        'profile_subscribed',
        'profile_notify',
        'profile_company',
        'profile_city',
        'profile_inn',
        'profile_site',
        'profile_bank',
        'profile_bik',
        'profile_bank_account',
        'profile_corr_account',
        'profile_kpp',
        'profile_ogrn',
        'profile_balance',
        'profile_timeout',
        'profile_credit',
        'profile_discount',
        'profile_discounts',
        'profile_discounts',
        'profile_price_id',
        'profile_delivery',
        'profile_icon'
    ];
}
