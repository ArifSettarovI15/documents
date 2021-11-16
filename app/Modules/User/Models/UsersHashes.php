<?php


namespace App\Modules\User\Models;


/**
 * @method static create(array $array)
 */
class UsersHashes extends \Eloquent
{
    public $table = 'users_hashes';

    protected $fillable = ['hash', 'user_id', 'data'];
    public $timestamps = false;
}
