<?php


namespace App\Modules\Contracts\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array[] $array)
 * @method static where(string $string, int $type_id)
 * @property int|mixed cts_contract_type_id
 * @property mixed cts_service_id
 */
class ContractTypeServicesModel extends Model
{
    protected $table = "contract_types_services";
    protected $primaryKey = "cts_id";
    public $timestamps = false;
    protected $fillable = [
        'cts_contract_type_id',
        'cts_service_id'
    ];

    protected $fields_names = [
        'cts_contract_type_id' => 'Услуга для типа договора',
        'cts_service_id' => 'Услуга для типа договора',
    ];

    public function getFieldName($key)
    {
        return $this->fields_names[$key];
    }
}
