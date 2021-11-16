<?php


namespace App\Modules\Clients\Models;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ContractEmailsModel
 * @package App\Modules\Contracts\Models
 * @mixin Builder
 */

class ClientsEmailsModel extends Model
{
    protected $table = 'emails';
    protected $primaryKey = 'email_id';
    public $timestamps = false;

    protected $fillable = [
        'email_client_id',
        'email_email',
        'email_name',
        'email_position',
    ];
    protected $fields_names = [
        'email_email' => 'Email списка email-лов',
        'email_name' => 'ФИО сотрудника списка email-лов',
        'email_position' => 'Должность сотрудника списка email-лов',
    ];

    public function getFieldName($key)
    {
        return $this->fields_names[$key];
    }
}
