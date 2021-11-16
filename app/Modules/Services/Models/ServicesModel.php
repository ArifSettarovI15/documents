<?php


namespace App\Modules\Services\Models;



use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed service_id
 * @mixin Builder
 */
class ServicesModel extends Model
{
    /**
     * @method create(array $request)
     * @method paginate(int $int)
     * @method where(string $string, $id)
     * @property ServicesModel service_id
     */
    protected $casts = [
        'is_published' => 'boolean',
    ];

    protected $table = 'services';

    protected $primaryKey = 'service_id';

    public $timestamps = false;

    protected $fillable = [
        'service_title',
        'service_writen',
        'service_price',
        'service_period',
        'service_status',
    ];

    protected $fields_names = [
        'service_title' => 'Заголовок услуги',
        'service_writen' => 'Написание услуги',
        'service_price'=> 'Стоимость услуги',
        'service_period'=> 'Период предоставления услуги',
        'service_status'=> 'Статус услуги',
    ];

    public function getFieldName($key): string
    {
        return $this->fields_names[$key];
    }

}
