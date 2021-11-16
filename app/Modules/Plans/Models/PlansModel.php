<?php

namespace App\Modules\Plans\Models;

use App\Modules\Contracts\Models\ContractsModel;
use Illuminate\Database\Eloquent\Model;


/**
 * @property mixed plan_contract
 * @method static where(string $string, int $int)
 * @method static create(array $array)
 * @mixi
 */
class PlansModel extends Model
{
    protected $table = 'plans';
    protected $primaryKey = 'plan_id';
    public $timestamps = false;

    protected $fillable = [
      'plan_contract',
      'plan_day',
      'plan_document',
      'plan_status',
      'plan_success_this_month',
      'plan_next_update',
    ];

    protected $fields_names = [
        'plan_contract' => 'Договора для плана',
        'plan_day' => 'День выполнения плана',
        'plan_document'=> 'Шаблон счета плана',
        'plan_status'=> 'Статус плана',
        'plan_success_this_month'=> 'Статус плана на текущий месяц',
    ];

    public function getFieldName($key): string
    {
        return $this->fields_names[$key];
    }

    public function getPlanContractNameAttribute(): string
    {
        $contract= ContractsModel::where('contract_id',$this->plan_contract)->first();

        return "Договор № ".$contract->contract_number.' от '.$contract->contract_date;
    }
}
