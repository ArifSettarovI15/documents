<?php

namespace App\Modules\Companies\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method paginate(int $int)
 * @method create(array $all)
 * @mixin Builder
 */
class CompaniesModel extends Model
{
    protected $table = 'companies';
    protected $primaryKey = 'company_id';
    public $timestamps = false;

    protected $fillable=
        [
            'company_type',
            'company_name',
            'company_director',
            'company_address',
            'company_phone',
            'company_email',
            'company_inn',
            'company_bank',
            'company_bik',
            'company_bill',
            'company_status',
        ];
    protected $fields_names = [
        'company_type' => 'Тип организации-поставщика',
        'company_name' => 'Название организации-поставщика',
        'company_director' => 'ФИО директора организации-поставщика',
        'company_address'=> 'Адрес организации-поставщика',
        'company_phone'=> 'Телефон организации-поставщика',
        'company_email'=> 'Email организации-поставщика',
        'company_inn'=> 'ИНН организации-поставщика',
        'company_bank'=> 'Банк организации-поставщика',
        'company_bik'=> 'Бик банка организации-поставщика',
        'company_bill'=> 'Номер счета организации-поставщика',
        'company_status'=> 'Статус организации-поставщика',
    ];

    public function getFieldName($key)
    {
        return $this->fields_names[$key];
    }
}
