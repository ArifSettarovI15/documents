<?php




namespace App\Modules\Contracts\Models;

use App\Modules\Services\Models\ServicesModel;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer client_id
 * @property int|mixed cs_contract_id
 * @property mixed cs_price
 * @property mixed cs_service_id
 * @property mixed cs_service_period
 * @method static where(string $string, int $id)
 * @method static find(string $string, int $id)
 */

class ContractsServicesModel extends Model
{
    /**
     * @method create(array $request)
     * @method paginate(int $int)
     * @method where(string $string, $id)
     * @property ContractsServicesModel contract_id
     */

    protected $table = 'contracts_services';
    protected $primaryKey = 'cs_id';
    public $timestamps = false;

    protected $fillable = [
        'cs_contract_id',
        'cs_service_id',
        'cs_service_period',
        'cs_price',
    ];

    protected $fields_names = [
        'cs_contract_id' => 'Услуга для договора',
        'cs_service_id' => 'Услуга для договора',
        'cs_service_period' => 'Период предоставления услуги для договора',
        'cs_price' => 'Стоимость услуги',
    ];

    public function getFieldName($key)
    {
        return $this->fields_names[$key];
    }
    public function getCsServiceWritenAttribute(){
        return ServicesModel::where('service_id', $this->cs_service_id)->pluck('service_writen')->first();
    }

}
